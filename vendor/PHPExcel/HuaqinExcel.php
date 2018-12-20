<?php

/**
 * excel数据操作
 * 
 * @version $Id: HuaqinExcel.php 346 2015-05-19 12:07:06Z chenhua $
 *
 */
class HuaqinExcel {
	
	// 最近错误信息
	protected $error            =   '';
	
	/**
	 +----------------------------------------------------------
	 * Export Excel
	 +----------------------------------------------------------
	 * @param $expTitle     string File name
	 +----------------------------------------------------------
	 * @param $expCellName  array  Column name
	 +----------------------------------------------------------
	 * @param $expTableData array  Table data
	 +----------------------------------------------------------
	 */
	public function exportExcel($expTitle,$expCellName,$expTableData){
		$xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
		$fileName = $expTitle.date('_YmdHis');//or $xlsTitle 文件名称可根据自己情况设定
		$cellNum = count($expCellName);
		$dataNum = count($expTableData);
		vendor('PHPExcel.PHPExcel');
		$objPHPExcel = new \PHPExcel();
		$cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');
	
		/* 设置当前的sheet */
		$objActSheetIndex = $objPHPExcel->setActiveSheetIndex(0);
		
		$objActSheet = $objPHPExcel->getActiveSheet(0);		
		/* sheet标题 */
		$objActSheet->setTitle($expTitle);
		
		$ascii = 65;
		$cv = '';
		$objActSheet->mergeCells('A1:'.$cellName[$cellNum-1].'1');//合并单元格
		$objActSheetIndex->setCellValue('A1', $expTitle.'  Export time:'.date('Y-m-d H:i:s'));
		foreach($expCellName as $key => $field){
			$objActSheet->setCellValue($cv.chr($ascii).'2', $field[1]);
			$ascii++;
			if($ascii == 91){
				$ascii = 65;
				$cv .= chr(strlen($cv)+65);
			}
		}
		$ascii = 65;
		$cv = '';
		$i = 3;
		foreach($expTableData as $val){
			foreach($expCellName as $field){
				$objActSheet->getColumnDimension($cv.chr($ascii))->setWidth('15');
				if(isset($field[2])) {
					switch ($field[2]) {
						//防止使用科学计数法，在数据前加空格
						case 'longNumber':
							$objActSheet->setCellValue($cv.chr($ascii).$i, ' '.$val[$field[0]]);
							break;
						case 'datetime':
							$objActSheet->setCellValue($cv.chr($ascii).$i, date('Y-m-d',$val[$field[0]]));
							break;
						default: 
							$objActSheet->setCellValue($cv.chr($ascii).$i, $val[$field[0]]);
							break;
					}
				} else {
					$objActSheet->setCellValue($cv.chr($ascii).$i, $val[$field[0]]);
				}
				$ascii++;
				if($ascii == 91){
					$ascii = 65;
					$cv .= chr(strlen($cv)+65);
				}
			}
			$ascii = 65;
			$cv = '';
			$i++;
		}

		/* 生成到浏览器，提供下载 */
		ob_end_clean();  //清空缓存
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
		header("Content-Type:application/force-download");
		header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		header("Content-Disposition:attachment;filename=$fileName.xlsx");//attachment新窗口打印inline本窗口打印
		header("Content-Transfer-Encoding:binary");	
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	}
	
	/**
	 +----------------------------------------------------------
	 * Import Excel
	 +----------------------------------------------------------
	 * @return mixed
	 +----------------------------------------------------------
	 */
	public function importExecl($file = null){
		header("Content-Type:text/html;charset=utf-8");
        if(!$file)
        {
            //上传文件
            $Files = D('Huaqin/Files');
            //C('FILE_UPLOAD.autoSave', false);// 不自动保存文件到数据库
            C('FILE_UPLOAD.exts', array('xls', 'xlsx'));// 设置附件上传类
            $info = $Files->upload($_FILES, C('FILE_UPLOAD'));
            if ($info) {
                $file = C('FILE_UPLOAD.rootPath').$info['excelData']['sourceFile'];
                $exts = $info['excelData']['ext'];
            } else {
                $this->error = $Files->getError();
                return false;
            }
        }else{
            $extend = pathinfo($file);
            $exts = strtolower($extend["extension"]);
        }
		
		if(!file_exists($file)){
			$this->error = '文件不存在';
			return false;
		}
		
		//解析文件
		Vendor("PHPExcel.PHPExcel.IOFactory");
		if($exts == 'xls'){
			$objReader = PHPExcel_IOFactory::createReader('Excel5');
			$objReader = PHPExcel_IOFactory::createReader('Excel5');
		}else if($exts == 'xlsx'){
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
		}	
		
		try{
			$PHPReader = $objReader->load($file);
		}catch(Exception $e){}
		if(!isset($PHPReader)) {
			$this->error = '读取数据失败!';
			return false;
		}
		$allWorksheets = $PHPReader->getAllSheets();
		$i = 0;
		foreach($allWorksheets as $objWorksheet){
			$sheetname=$objWorksheet->getTitle();
			$allRow = $objWorksheet->getHighestRow();//多少行
			$highestColumn = $objWorksheet->getHighestColumn();//多少列
			$allColumn = PHPExcel_Cell::columnIndexFromString($highestColumn);
			$array[$i]["Title"] = $sheetname;
			$array[$i]["Cols"] = $allColumn;
			$array[$i]["Rows"] = $allRow;
			$arr = array();
			$isMergeCell = array();
			foreach ($objWorksheet->getMergeCells() as $cells) {//合并单元格
				foreach (PHPExcel_Cell::extractAllCellReferencesInRange($cells) as $cellReference) {
					$isMergeCell[$cellReference] = true;
				}
			}
			for($currentRow = 1 ;$currentRow<=$allRow;$currentRow++){
				$row = array();
				for($currentColumn=0;$currentColumn<$allColumn;$currentColumn++){;
				$cell =$objWorksheet->getCellByColumnAndRow($currentColumn, $currentRow);
				$afCol = PHPExcel_Cell::stringFromColumnIndex($currentColumn+1);
				$bfCol = PHPExcel_Cell::stringFromColumnIndex($currentColumn-1);
				$col = PHPExcel_Cell::stringFromColumnIndex($currentColumn);
				$address = $col.$currentRow;
				$value = $objWorksheet->getCell($address)->getValue();
				if(substr($value,0,1)=='='){
					$this->error = '不能用公式!';
					return false;
					exit;
				}
				if($cell->getDataType()==PHPExcel_Cell_DataType::TYPE_NUMERIC){
					$cellstyleformat=$cell->getParent()->getStyle( $cell->getCoordinate() )->getNumberFormat();
					$formatcode=$cellstyleformat->getFormatCode();
					if (preg_match('/^([$[A-Z]*-[0-9A-F]*])*[hmsdy]/i', $formatcode)) {
						$value=gmdate("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($value));
					}else{
						$value=PHPExcel_Style_NumberFormat::toFormattedString($value,$formatcode);
					}
				}
				if($isMergeCell[$col.$currentRow]&&$isMergeCell[$afCol.$currentRow]&&!empty($value)){
					$temp = $value;
				}elseif($isMergeCell[$col.$currentRow]&&$isMergeCell[$col.($currentRow-1)]&&empty($value)){
					$value=$arr[$currentRow-1][$currentColumn];
				}elseif($isMergeCell[$col.$currentRow]&&$isMergeCell[$bfCol.$currentRow]&&empty($value)){
					$value=$temp;
				}
				$row[$currentColumn] = $value;
				}
				$arr[$currentRow] = $row;
			}
			$array[$i]["Content"] = $arr;
			$i++;
		}
		unset($objWorksheet);
		unset($PHPReader);
		unset($PHPExcel);
		//unlink($file);
		return $array;
	}
	
	public function getError() {
		return $this->error;
	}
}