<?php
	Class db{
		private $con;
		private $data;
		
		public function __construct($data=''){
			$this->data = $data;
			$this->con = mysqli_connect('localhost','root','root','test3');
			mysqli_query($this->con,"set names utf8");
		}
		
		//插入数据库
		public function add(){
			$sql = "SELECT * FROM socket_test WHERE v_id={$this->data['v_id']} AND u_id={$this->data['u_id']} AND socket_id={$this->data['socket_id']}";
			$sqlObj = mysqli_query($this->con,$sql);
			if($sqlObj->num_rows == 0){
				$sql = "INSERT INTO socket_test (socket_id, v_id, u_id,u_name) VALUES ({$this->data['socket_id']},{$this->data['v_id']},{$this->data['u_id']},'{$this->data['u_name']}')";
				return mysqli_query($this->con,$sql);
			}
		}

		//获取数据
		public function find($field = '*'){
			$sql = "SELECT {$field} FROM socket_test WHERE v_id={$this->data['v_id']}";
			$result = mysqli_query($this->con,$sql);
			if($result->num_rows != 0){
				$arr = mysqli_fetch_all($result,MYSQLI_ASSOC);
				return $this->dealData($arr);
			}else{
				return false;
			}
		}

		//更具进程id删除映射
		public function del($socket_id=''){
			if($socket_id == ''){
				$socket_id = $this->data['socket_id'];
			}
			$sql = "DELETE FROM socket_test WHERE socket_id={$socket_id}";
			return mysqli_query($this->con,$sql);
		}

		//重新设置$data的值
		public function setData($data){
			$this->data = $data;
		}

		//返回需要的数据处理
		private function dealData($arr){
			$data['str'] = '';
			$temp_arr = array();
			foreach($arr as $key => $val){
				$data['str'] .= $val['u_name'].',';
				array_push($temp_arr,$val['socket_id']);
			}
			$data['socket_id'] = $temp_arr;
			return $data;
		}
	}
?>