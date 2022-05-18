<?php

namespace App\Controllers;

use App\Controller;
use App\MongoModel;

/**
 * MongoDB demo controller.
 */
class MongoDemoController extends Controller
{
    /**
     * Unique instance of this class.
     *
     * @var self|null
     */
    protected static $_uniqueInstance = null;

    /**
     * Get the unique instance of this class.
     *
     * @return self
     */
    public static function getInstance(): self
    {
        if (self::$_uniqueInstance == null) self::$_uniqueInstance = new self();
        return self::$_uniqueInstance;
    }

    /**
     * Constructor.
     *
     * @return void
     */
    protected function __construct()
    {
        $this->_className = basename(__FILE__, '.php');
    }

    /**
     * Demo method of `insert`.
     *
     * @return void
     */
    public function insert(): void
    {
        $collectionName = 'Demo';
        $collection = MongoModel::getInstance('MONGO')->setCollection($collectionName);
        $documents = [
            // [
            //     'name' => 'Ayano Keiko',
            //     'nickname' => 'Silica'
            // ],
            // [
            //     'name' => 'Shinozaki Rika',
            //     'nickname' => 'Lisbeth'
            // ],
            [
                'name' => 'Kayaba Akihiko',
                'nickname' => 'Heathcliff'
            ]
        ];
        $data = $collection->insert($documents);
        $output = json_encode($data, 448);
        view('Demo.Demo', [
            'title' => 'Insert - ' . PROJECT_NAME,
            'output' => $output
        ]);
    }

    /**
     * Demo method of `count`.
     *
     * @return void
     */
    public function count(): void
    {
        $collectionName = 'Demo';
        $collection = MongoModel::getInstance('MONGO')->setCollection($collectionName);
        $data = $collection->count();
        $output = json_encode($data, 448);
        view('Demo.Demo', [
            'title' => 'Count - ' . PROJECT_NAME,
            'output' => $output
        ]);
    }

    /**
     * Demo method of `select`.
     *
     * @return void
     */
    public function select(): void
    {
        $collectionName = 'Demo';
        $collection = MongoModel::getInstance('MONGO')->setCollection($collectionName);
        $filter = [
            'name' => 'Kirigaya Kazuto'
        ];
        $data = $collection->select($filter);
        $output = json_encode($data, 448);
        view('Demo.Demo', [
            'title' => 'Select - ' . PROJECT_NAME,
            'output' => $output
        ]);
    }

    /**
     * Demo method of `update`.
     *
     * @return void
     */
    public function update(): void
    {
        $collectionName = 'Demo';
        $collection = MongoModel::getInstance('MONGO')->setCollection($collectionName);
        $filter = [
            // 'name' => 'Kirigaya Kazuto'
            'nickname' => 'Heathcliff'
        ];
        $update = [
            '$set' => [
                'status' => 'Dead'
            ]
        ];
        $data = $collection->update($filter, $update);
        $output = json_encode($data, 448);
        view('Demo.Demo', [
            'title' => 'Update - ' . PROJECT_NAME,
            'output' => $output
        ]);
    }

    /**
     * Demo method of `delete`.
     *
     * @return void
     */
    public function delete(): void
    {
        $collectionName = 'Demo';
        $collection = MongoModel::getInstance('MONGO')->setCollection($collectionName);
        $filter = [
            'nickname' => 'Heathcliff'
        ];
        $data = $collection->delete($filter);
        $output = json_encode($data, 448);
        view('Demo.Demo', [
            'title' => 'Delete - ' . PROJECT_NAME,
            'output' => $output
        ]);
    }
}
