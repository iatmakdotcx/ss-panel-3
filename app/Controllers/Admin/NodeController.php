<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Models\Node;
use App\Models\TrafficLog;

class NodeController extends AdminController
{
    public function index($request, $response, $args)
    {
        $nodes = Node::all();
        return $this->view()->assign('nodes', $nodes)->display('admin/node/index.tpl');
    }

    public function create($request, $response, $args)
    {
        $method = Node::getCustomerMethod();
        return $this->view()->assign('method', $method)->display('admin/node/create.tpl');
    }

    public function add($request, $response, $args)
    {
        $node = new Node();
        $node->name = $request->getParam('name');
        $node->server = $request->getParam('server');
        $node->method = $request->getParam('method');
        $node->custom_method = $request->getParam('custom_method');
        $node->traffic_rate = $request->getParam('rate');
        $node->info = $request->getParam('info');
        $node->type = $request->getParam('type');
        $node->status = $request->getParam('status');
        $node->sort = $request->getParam('sort');
        if (!$node->save()) {
            $rs['ret'] = 0;
            $rs['msg'] = "添加失败";
            return $response->getBody()->write(json_encode($rs));
        }
        $rs['ret'] = 1;
        $rs['msg'] = "节点添加成功";
        return $response->getBody()->write(json_encode($rs));
    }

    public function edit($request, $response, $args)
    {
        $id = $args['id'];
        $node = Node::find($id);
        if ($node == null) {
			return $this->redirect($response, '/admin/node');
        }
        $method = Node::getCustomerMethod();
        return $this->view()->assign('node', $node)->assign('method', $method)->display('admin/node/edit.tpl');
    }

    public function update($request, $response, $args)
    {
        $id = $args['id'];
        $node = Node::find($id);

        $node->name = $request->getParam('name');
        $node->server = $request->getParam('server');
        $node->method = $request->getParam('method');
        $node->custom_method = $request->getParam('custom_method');
        $node->traffic_rate = $request->getParam('rate');
        $node->info = $request->getParam('info');
        $node->type = $request->getParam('type');
        $node->status = $request->getParam('status');
        $node->sort = $request->getParam('sort');
        if (!$node->save()) {
            $rs['ret'] = 0;
            $rs['msg'] = "修改失败";
            return $response->getBody()->write(json_encode($rs));
        }
        $rs['ret'] = 1;
        $rs['msg'] = "修改成功";
        return $response->getBody()->write(json_encode($rs));
    }


    public function delete($request, $response, $args)
    {
        $id = $args['id'];
        $node = Node::find($id);
        if (!$node->delete()) {
            $rs['ret'] = 0;
            $rs['msg'] = "删除失败";
            return $response->getBody()->write(json_encode($rs));
        }
        $rs['ret'] = 1;
        $rs['msg'] = "删除成功";
        return $response->getBody()->write(json_encode($rs));
    }

    public function deleteGet($request, $response, $args)
    {
        $id = $args['id'];
        $node = Node::find($id);
        $node->delete();
        return $this->redirect($response, '/admin/node');
    }
	public function trfl($request, $response, $args)
    {
        $id = $args['id'];
        $node = Node::find($id);
        if ($node == null) {
			return $this->redirect($response, '/admin/node');
        }
		$pieCV = TrafficLog::join('user','user_traffic_log.user_id','=','user.id')->groupBy('user_id')
		->selectRaw('port as name,sum(user_traffic_log.d+user_traffic_log.u) as value')->
		where('user_traffic_log.node_id', $node->id)->get()->toJSON();
		
		$lineCV = TrafficLog::where('node_id', $node->id)
		->selectRaw('DATE_FORMAT(FROM_UNIXTIME(log_time),\'%Y-%m-%d\') as name,sum(u+d) as value')
		->groupBy("name")->get()->toJSON();	
				
        return $this->view()->assign('node', $node)->assign('pieChartValue', $pieCV)->assign('lineChartValue', $lineCV)->display('admin/node/traffic.tpl');
    }
}