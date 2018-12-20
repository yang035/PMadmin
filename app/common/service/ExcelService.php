<?php
namespace app\common\service;

class ExcelService extends Service{
	

	/*
	 * 读取上传文件
	*/
	public function readUploadFile($filePath,$format,$num=7000,$checkformat){
		$return = array('status'=>0,'data'=>'');
		//检测文件有效性
		if (! is_file ( $filePath )){
			$return['data'] = '读取文件异常,请重试';
			return $return;
		}
		 
		//导入PHPexcel
		vendor ( 'PHPExcel.PHPExcel' );
		vendor ( 'PHPExcel.PHPExcel.Writer.Excel5' ); // 用于其他低版本xls
		vendor ( 'PHPExcel.PHPExcel.Writer.Excel2007' ); // 用于 excel-2007 格式
		$PHPExcel = new \PHPExcel();
		 
		/**
		 * 默认用excel2007读取excel，若格式不对，则用之前的版本进行读取
		*/
		$PHPReader = new \PHPExcel_Reader_Excel2007 ();
		if (! $PHPReader->canRead ( $filePath )) {
			$PHPReader = new \PHPExcel_Reader_Excel5 ();
			if (! $PHPReader->canRead ( $filePath )) {
				// echo 'no Excel';
				$return['data'] = '读取失败，请重试';
				return $return;
			}
		}
		 
	
		$PHPExcel = $PHPReader->load ( $filePath );
		$currentSheet = $PHPExcel->getActiveSheet (); // 读取excel文件中的激活的工作表
		$highestColumn = $currentSheet->getHighestColumn (); // 取得最大的列号
		$highestRow = $currentSheet->getHighestRow (); // 取得一共有多少行
		// dump($highestRow);
		/* $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); */
		$excelData = array (); // 声明数组
		$error = array();
		//	print_r($currentSheet->getCe);
		/* if($currentSheet->getCell('A1')->getValue()!='MAIN_ID'){
			$return['data'] = '数据格式出错请检查！';
			return $return;
		} */

		
		if($checkformat){
			foreach($checkformat as $k=>$v){
				if(trim($currentSheet->getCell($k.'1')->getValue())!= $v){
					$return['data'] = '<span style="color:red">数据格式出错请检查！'.$k.'1列名称,应该为"'.$v.'"</span>';
					return $return;
				}
			}
		}
	
		for($row=2;$row<=$num;$row++){
			$rows = array();
			foreach($format as $k=>$v){
				$value =  (string)$currentSheet->getCell ( $k.$row )->getValue ();
			
				if($v=='line' && empty($value)){
					break 2;
				} 
				$value = !$value ? '':$value;
				$rows[$k] = $value;
			}
			$excelData[] = $rows;
				
		}
	
		
		if(!empty($error)){
			$return['data'] = implode('', $error);
			return $return;
		}
		 
		//存在有效数据
		if(count($excelData)>0){
			$return['status'] = 1;
			$return['data'] = $excelData;
		}else{
			$return['data'] = '没有可以导入的数据请检查！';
		}
	
		return $return;
	}
	
}