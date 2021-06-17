<?php

namespace App\Http\Controllers\Api;

use App\Services\NodeService;
use App\Services\ShardService;
use App\Services\SolrService;
use App\Services\ZookeeperService;

class SolrCloudApiController {
    private $zookeeperService;
    private $solrService;
    private $shardService;
    private $nodeService;
    
    public function __construct(SolrService $solrService
        , ZookeeperService $zookeeperService
        , ShardService $shardService
        , NodeService $nodeService) {
        
        $this->zookeeperService = $zookeeperService;
        $this->solrService = $solrService;
        $this->shardService = $shardService;
        $this->nodeService = $nodeService;
    }
    
    
    /**
     * 샤드 리스트 조회
     * @return \Illuminate\Http\JsonResponse
     */
    public function getShards() {
        $result = $this->shardService->getShards();
        return response()->json($result);
    }
    
    /**
     * 노드 리스트 및 추가정보 조회
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNodeInfo() {
        $result = $this->nodeService->getNodeExtra();
        return response()->json($result);
    }
    
    /**
     * 노드 리스트 조회
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNodes() {
        $result = $this->nodeService->getNodes();
        return response()->json($result);
    }
    
    /**
     * 리플레카가 없는 샤드 리스트 조회
     * @return \Illuminate\Http\JsonResponse
     */
    public function single() {
        $result = $this->shardService->getSingleShard();
        return response()->json($result);
    }
    
    /**
     * 실행중인 명령어 리스트 조회
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCollectionOps() {
        $operations = $this->solrService->getCollectionOperations();
        return response()->json($operations);
    }
    
    /**
     * 에러 명령어 리스트 조회
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCollectionOpsErrors() {
        $result = $this->solrService->getCollectionErrorOperations();
        return response()->json($result);
    }
    
    /**
     * running map 조회
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRunningMap() {
        $runningMap = $this->zookeeperService->getRunningMap();
        return response()->json($runningMap);
    }
    
    /**
     * async 명령 상태 조회
     * @param $asyncId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRequestStatus($asyncId) {
        $requestStatus = $this->solrCloudService->getRequestStatus($asyncId);
        return response()->json($requestStatus);
    }
    
    /**
     * state 조회
     * @return \Illuminate\Http\JsonResponse
     */
    public function getState() {
        $state = $this->zookeeperService->getState();
        return response()->json($state);
    }
    
    /**
     * shard graph 조회
     * @return \Illuminate\Http\JsonResponse
     */
    public function getShardGraph() {
        $state = $this->zookeeperService->getState();
        $shardGraph = $this->solrService->getShardGraph($state);
        return response()->json($shardGraph);
    }
    
    /**
     * 샤드 카운팅 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getShardCountUsageDisk() {
        $result = $this->solrService->getShardCountDiskUsage();
        return response()->json($result);
    }
    
    /**
     * 라풀리카 SPLIT 전후 체크
     */
    public function getReplicaSplitShard() {
        $result = $this->solrService->getReplicaSplitShard();
        return response()->json($result);
    }
    
    /**
     * 노드당 샤드 수 계산
     * @param int $nodeCnt
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAvgShard($nodeCnt=67) {
        $result = $this->solrService->getAvgShardPerNode($nodeCnt);
        return response()->json($result);
    }
    
    /**
     * 샤드당 리플리카수 계산
     * @return \Illuminate\Http\JsonResponse
     */
    public function getShardCnt() {
        $result = $this->solrService->getShardReplCnt();
        return response()->json($result);
    }
}