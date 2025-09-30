<?php

namespace App\Services;

use Google\Cloud\Core\Exception\ServiceException;
use Google\Cloud\Storage\StorageClient;
use Google\Cloud\Storage\StorageObject;

class GoogleStorageService
{
    protected ?StorageClient $client;

    public function __construct()
    {
        $keyFilePath = storage_path('app/private/') . getenv('GOOGLE_SERVICE_ACCOUNT_FILE');
        $this->client = is_file($keyFilePath)
            ? new StorageClient([
                'keyFilePath' => $keyFilePath,
                'projectId' => getenv('GOOGLE_PROJECT_ID')
            ])
            : null;
        set_time_limit(0);
    }

    /**
     * @param string $bucketName
     * @return string
     */
    private function getGeneralBucket(string $bucketName): string
    {
        switch($bucketName) {
            case 'api/currencies':
                return getenv('GOOGLE_CDN_BUCKET');

            default:
                return getenv('GOOGLE_PRIVATE_BUCKET');
        }
    }

    /**
     * @param string $bucketName
     * @param string $fileName
     * @param string $fileContents
     * @param array $metadata
     * @return StorageObject
     */
    public function pushOnBucket(string $bucketName, string $fileName, string $fileContents, array $metadata = []): StorageObject
    {
        $bucketGenericName = $this->getGeneralBucket($bucketName);
        $bucket = $this->client->bucket($bucketGenericName);
        $storageName = ($bucketGenericName == getenv('GOOGLE_PRIVATE_BUCKET') ? getenv('GOOGLE_MACHINE_ID_') . '/' : '') . $bucketName . '/' . $fileName;
        $options = ['name' => $storageName];
        if (!empty($metadata)) {
            $options['metadata'] = $metadata;
        }
        return $bucket->upload($fileContents, $options);
    }

    /**
     * Give image URL on CDN bucket only
     *
     * @param string $bucketName
     * @param string $fileName
     * @return string
     */
    public function getFileUrl(string $bucketName, string $fileName): string
    {
        $bucketGenericName = $this->getGeneralBucket($bucketName);
        return $bucketGenericName != getenv('GOOGLE_PRIVATE_BUCKET') ? getenv('GOOGLE_CDN_URL') . $bucketName . '/' . $fileName : '';
    }

    /**
     * @param string $bucket
     * @return array
     * @throws ServiceException
     */
    public function isBucketWritable(string $bucket): array
    {
        $bucket = $this->client->bucket($bucket);
        $isWritable = $bucket->isWritable();
        return ['isWritable' => $isWritable];
    }
}
