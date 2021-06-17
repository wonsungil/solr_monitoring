<?php
namespace App\Http\Controllers\Api;

use App\Services\SolrCloudService;
use App\Services\ZookeeperService;
use GuzzleHttp\Client;

class TestController {
    
    private $solrCloudService;
    private $zookeeperService;
    
    public function __construct(SolrCloudService $solrCloudService, ZookeeperService $zookeeperService) {
        $this->solrCloudService = $solrCloudService;
        $this->zookeeperService = $zookeeperService;
    }
    
    public function getSolrDocsDate() {
        return response()->json("test");
    }
}