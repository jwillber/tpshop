<?php
/**
 * tpshop
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * 采用最新Thinkphp6
 * ============================================================================
 * Author: 聂晓克     
 * Date: 2017-12-12
 */
namespace app\admin\controller;
use think\facade\View;
use app\admin\logic\GoodsLogic;
use think\facade\Db;

class Import extends Base {

 	public function index(){
            header("Content-type: text/html; charset=utf-8");
exit("请联系TPshop官网客服购买高级版支持此功能");
  	}

  	//上传的csv文件及图片文件 返回数组结果
	public function upload_data(){
                         
        header("Content-type: text/html; charset=utf-8");
exit("请联系TPshop官网客服购买高级版支持此功能");
	}

	/**
	 * 读取文件csv
	 * @param $csvFileName |文件名
	 * @param int $line |读取几行，默认全部读取
	 * @param int $offset |从第几行开始读，默认从第一行读取
	 * @return array|string
	 */
	public function getCsv($csvFileName, $line = 0, $offset = 0){
		$handle = fopen($csvFileName,'r');//打开文件，如果打开失败，本函数返回 FALSE。
		if(!$handle){
			return '文件打开失败';
		}
		//fgetcsv() 出错时返回 FALSE，包括碰到文件结束时。
		$i = 0;//用于记录while的循环次数，方便与$line,$offset比较
		$list = [];
		while($data = fgetcsv($handle)){
			$arr = [];
			//小于偏移量则不读取,但$i仍然需要自增
			if($i < $offset && $offset){
				$i++;
				continue;
			}
			//大于读取行数则退出
			if($i > $line && $line){
				break;
			}
			$i++;
			foreach ($data as $key => $value) {
				$content = iconv("gbk","utf-8//IGNORE",$value);//转化编码
				$arr[] = $content;//至于如何处理这个结果，需要根据实际情况而定
			}
			$list[] = $arr;
		}
		return $list;
	}

	public function add_data(){
        header("Content-type: text/html; charset=utf-8");
exit("请联系TPshop官网客服购买高级版支持此功能");
	}

	/**
	 * csv文件转码为utf8
	 * @param  string 文件路径
	 * @return resource  打开文件后的资源类型
	 */
	private function fopen_utf8($filename){  
        $encoding='';  
        $handle = fopen($filename, 'r');  
        $bom = fread($handle, 2);  
    	//fclose($handle);  
        rewind($handle);  
       
        if($bom === chr(0xff).chr(0xfe)  || $bom === chr(0xfe).chr(0xff)){  
            // UTF16 Byte Order Mark present  
            $encoding = 'UTF-16';  
        } else {  
            $file_sample = fread($handle, 1000) + 'e'; //read first 1000 bytes  
            // + e is a workaround for mb_string bug  
            rewind($handle);  
            $encoding = mb_detect_encoding($file_sample , 'UTF-8, UTF-7, ASCII, EUC-JP,SJIS, eucJP-win, SJIS-win, JIS, ISO-2022-JP');  
        }  
        if ($encoding){  
            stream_filter_append($handle, 'convert.iconv.'.$encoding.'/UTF-8');  
        }  
        return ($handle);  
    } 

    //csv文件读取为数组形式返回
	private function str_getcsv($string, $delimiter=',', $enclosure='"'){ 
        $fp = fopen('php://temp/', 'r+');
        fputs($fp, $string);
        rewind($fp);
        while($t = fgetcsv($fp, strlen($string), $delimiter, $enclosure)) {
            $r[] = $t;
        }
        if(count($r) == 1) 
            return current($r);
        return $r;
    }

}