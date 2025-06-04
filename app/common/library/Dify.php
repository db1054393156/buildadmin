<?php

namespace app\common\library;

use Exception;
use GuzzleHttp\Client;

/**
 * Dify 工具类
 * 用于对接 Dify 工作流接口
 */
class Dify
{
    /**
     * Dify API Key
     * @var string
     */
    protected string $apiKey;

    /**
     * Dify API 地址
     * @var string
     */
    protected string $apiUrl = 'http://103.40.14.247/v1/workflows/run';

    /**
     * GuzzleHttp 客户端
     * @var Client
     */
    protected Client $client;

    /**
     * 构造函数
     * @param string $apiKey
     * @param string|null $apiUrl
     */
    public function __construct(string $apiKey, string $apiUrl = null)
    {
        $this->apiKey = $apiKey;
        if ($apiUrl) {
            $this->apiUrl = $apiUrl;
        }
        $this->client = new Client([
            'timeout' => 30,
        ]);
    }

    /**
     * 运行 Dify 工作流
     * @param array $inputs
     * @param string $user
     * @param string $responseMode
     * @return array|string
     * @throws Exception
     */
    public function runWorkflow(array $inputs, string $user, string $responseMode = 'streaming')
    {
        $headers = [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type'  => 'application/json',
        ];
        $body = [
            'inputs'        => $inputs,
            'response_mode' => $responseMode,
            'user'          => $user,
        ];
        try {
            $response = $this->client->post($this->apiUrl, [
                'headers' => $headers,
                'json'    => $body,
            ]);
            $contentType = $response->getHeaderLine('Content-Type');
            $result = (string)$response->getBody();
            if (stripos($contentType, 'application/json') !== false) {
                return json_decode($result, true);
            }
            return $result;
        } catch (Exception $e) {
            throw new Exception('Dify API 请求失败: ' . $e->getMessage());
        }
    }

    /**
     * 上传文件到 Dify
     * @param string $filePath 本地文件路径
     * @param string $user 用户标识
     * @return array|string
     * @throws Exception
     */
    public function uploadFile(string $filePath, string $user)
    {
        if (!file_exists($filePath)) {
            throw new Exception('文件不存在: ' . $filePath);
        }
        $url = preg_replace('/\/workflows\/run$/', '/files/upload', $this->apiUrl);
        $headers = [
            'Authorization' => 'Bearer ' . $this->apiKey,
        ];
        $multipart = [
            [
                'name'     => 'file',
                'contents' => fopen($filePath, 'r'),
                'filename' => basename($filePath),
            ],
            [
                'name'     => 'user',
                'contents' => $user,
            ],
        ];
        try {
            $response = $this->client->post($url, [
                'headers'   => $headers,
                'multipart' => $multipart,
            ]);
            $contentType = $response->getHeaderLine('Content-Type');
            $result = (string)$response->getBody();
            if (stripos($contentType, 'application/json') !== false) {
                return json_decode($result, true);
            }
            return $result;
        } catch (Exception $e) {
            throw new Exception('Dify 文件上传失败: ' . $e->getMessage());
        }
    }
} 