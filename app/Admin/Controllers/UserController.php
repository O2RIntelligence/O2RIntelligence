<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\UserController as baseUserController;
use Auth;
use  App\Admin\Models\Administrator;
use Illuminate\Support\MessageBag;

class UserController extends baseUserController {

    
	protected function grid() {
		$g = parent::grid();
		$g->column( 'api_password', 'API Password' );
		$g->column( 'partner_fee', 'Partner Fee' );
		return $g;
	}


	public function form() {
		$f = parent::form();
		$model = false;

		if ($f->isEditing()) { $id = request()->route()->parameter('user'); $model = $f->model()->find($id); }


		$f->text( 'api_password' );
		$f->text( 'partner_fee' )->help('Add a negative sign before number to use media cost instead of revenue total.');
		$f->hidden( 'api_token' );
		$f->hidden( 'adtelligent_account_id' );
		
		if( $model && !empty($model->api_password) ) {
			
			$channels = $model->request('dictionary/channel', [
				'limit' => 1000,
				'page' => 1
			]);
			$options = [];
			foreach($channels['data'] as $channel) {
				$options[$channel['id']] = $channel['name'] . ' #' . $channel['id'];
			}
			$f->multipleSelect('excluded_channels', 'Excluded Channels')->options($options)->value();
		}

        $f->saving(function (\Encore\Admin\Form $f) use ($model) {
			if(!empty($f->api_password)) {

				$auth = Administrator::APIAuth($f->username, $f->api_password);
				
				if(!$auth || !$auth['success']) {
					// throws an exception
					$error = new MessageBag([
						'title'   => 'Invalid Seat...',
						'message' => 'Seat API username and Password are malformed',
					]);

					return back()->with(compact('error'));
				}

				$f->api_token = $auth['data']['sessionData']['sid'];
				$f->adtelligent_account_id = $auth['data']['sessionData']['account_id'];
			}

		});
		return $f;
	}
    
}