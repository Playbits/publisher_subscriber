<?php

namespace App\Http\Controllers;

use Aws\Exception\AwsException;
use Aws\Sns\SnsClient;
use Illuminate\Http\Request;

class SubscriberController extends Controller {
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        //
    }
    public function subscribeTopic(Request $request, $topic) {
        $SnSclient = new SnsClient([
            'region' => 'us-west-2',
            'version' => '2010-03-31',
        ]);
        $url = $request->input('url');
        try {
            $createTopic = $SnSclient->createTopic([
                'Name' => $topic,
            ]);
            $TopicArn = $createTopic['TopicArn'];
            $output['TopicArn'] = $TopicArn;
            $output['TopicName'] = $topic;
        } catch (AwsException $e) {
            throw $e->getMessage();
        }
        try {
            $subscribe = $SnSclient->subscribe([
                'TopicArn' => $TopicArn,
                'Protocol' => 'http',
                'Endpoint' => $url,
            ]);
            var_dump($subscribe);
        } catch (AwsException $e) {
            var_dump($e->getMessage());
        }
        $output['Message'] = 'Topic created';
        $output['Status'] = true;
        return response()->json($output, 201);
    }

    private function subscribeToTopic($var = null) {

    }
}
