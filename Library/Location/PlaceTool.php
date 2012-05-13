<?php
class Location_PlaceTool {
  var $latitude_scale; 	//纬度方向的刻度,double
  var $longitude_scale; 	//经度方向的刻度,double
  var $real_size; 		//一度多高,double
  var $real_scale; 		//格子的高度，米,double

  public function __construct() { 
    $this->latitude_scale = 0.006; //纬度方向的刻度,double
    $this->longitude_scale = 0.006; //经度方向的刻度,double
    $this->real_size = 39250000/360; //一度多高,double
    $this->real_scale = 39250000 * $this->longitude_scale /360; //格子的高度，米,double
  } 

  /**
   * 计算两个经纬度的距离平方
   * @param lat1
   * @param lon1
   * @param lat2
   * @param lon2
   * @return
   */
  public function calDistanceSquare($lat1, $lon1, $lat2, $lon2){
    $y =(double)($this->calDistanceSquareWithSize($lat1, $lon1, $lat2, $lon2, $this->calLandscapeSize($lat1)));
    return $y;
  }

  /**
   * 计算两个经纬度的距离平方,不用每次算经度上的刻度
   * @param lat1
   * @param lon1
   * @param lat2
   * @param lon2
   * @param lon_scale
   * @return
   */
  public function calDistanceSquareWithSize($lat1, $lon1, $lat2, $lon2,$lon_size){
    $lat = (double)($lat1 - $lat2)*$this->real_size;
    $lon = (double)($lon1 - $lon2)*$lon_size;
    return $lat*$lat + $lon*$lon;
  }	
  /**
   * 把纬度转换为0-180，南极为0
   * @param latitude
   * @return
   */
  public function convertLat($latitude){
    $latitude = (double)90 + abs($latitude); 
    return $latitude;
  }

  /**
   * 经度转换为0-360，西经是180-360
   * @param longitude
   * @return
   */
  public function convertLon($longitude){
    if($longitude < 0) $longitude = 360 + $longitude;
    return (double) $longitude;
  }	

  /**
   * 计算指定经度和纬度所属格子的索引
   * @param latitude 纬度
   * @param longitude 经度
   * @return
   */
  public function  calIndexByLatLon($latitude, $longitude){
    $longitude = $this->convertLon($longitude);
    $latitude = $this->convertLat($latitude); 
    $lat = (int)($latitude/$this->latitude_scale);
    $lon = (int)($longitude/$this->longitude_scale);
    $lat = $lat<<16;
    return (int) $lat ^ $lon;
  }

  public function  calIndexByLatLonSmall($latitude, $longitude){
    $this->latitude_scale = 0.001; //纬度方向的刻度,double
    $this->longitude_scale = 0.001; //经度方向的刻度,double	
    $longitude = $this->convertLon($longitude);
    $latitude = $this->convertLat($latitude); 
    $lat = (int)($latitude/$this->latitude_scale);
    $lon = (int)($longitude/$this->longitude_scale);
    $lat = $lat<<16;
    return (int) $lat ^ $lon;
  }



  /**
   * 计算指定纬度下，单位纬度宽度
   * @param latitude 纬度
   * @return
   */
  public function  calLandscapeSize($latitude){
    $latitude = abs($latitude);
    return (double) $this->real_size * cos($latitude/180*PI());
  }	
  /**
   * 计算指定纬度下，格子在经度方向的宽度
   * @param latitude 纬度
   * @return
   */
  public  function  calLandscapeScale($latitude){
    $latitude = abs($latitude);
    return (double) $this->real_scale * cos($latitude/180*PI());
  }
  /**
   * 计算指定的范围跨越了哪些方块
   * @param latitude
   * @param longitude
   * @param radius
   * @return 方块索引列表
   */
  public function findIndex($latitude, $longitude, $radius){
    $lat_offset = (double)$radius/$this->real_scale;
    $lon_offset = (double)$radius/$this->calLandscapeScale($latitude);
    $lat = (double)$this->convertLat($latitude)/$this->latitude_scale;
    $lon = (double)$this->convertLon($longitude)/$this->longitude_scale;
    $lat_min = (int)($lat - $lat_offset);
    $lat_max = (int)($lat + $lat_offset);
    $lon_min = (int)($lon - $lon_offset);
    $lon_max = (int)($lon + $lon_offset);
    $index_size = ($lat_max-$lat_min+1)*($lon_max-$lon_min+1);
    $indexs=array();
    //$indexs = new Integer[index_size];
    $i = 0;
    for(; $lat_min<=$lat_max; $lat_min++){
      for($l=$lon_min; $l<=$lon_max; $l++){
        $indexs[$i] = ($lat_min<<16) ^ $l;
        $i++;
      }
    }
    return $indexs;
  }

  public function findIndexSmall($latitude, $longitude, $radius){
    $this->latitude_scale = 0.001; //纬度方向的刻度,double
    $this->longitude_scale = 0.001; //经度方向的刻度,double
    $lat_offset = (double)$radius/$this->real_scale;
    $lon_offset = (double)$radius/$this->calLandscapeScale($latitude);
    $lat = (double)$this->convertLat($latitude)/$this->latitude_scale;
    $lon = (double)$this->convertLon($longitude)/$this->longitude_scale;
    $lat_min = (int)($lat - $lat_offset);
    $lat_max = (int)($lat + $lat_offset);
    $lon_min = (int)($lon - $lon_offset);
    $lon_max = (int)($lon + $lon_offset);
    $index_size = ($lat_max-$lat_min+1)*($lon_max-$lon_min+1);
    $indexs=array();
    //$indexs = new Integer[index_size];
    $i = 0;
    for(; $lat_min<=$lat_max; $lat_min++){
      for($l=$lon_min; $l<=$lon_max; $l++){
        $indexs[$i] = ($lat_min<<16) ^ $l;
        $i++;
      }
    }
    return $indexs;
  }



  /**
   * 获得环形区域上的方格
   * @param latitude
   * @param longitude
   * @param radiusMin
   * @param radiusMax
   * @return Integer[]
   */
  static public function  findIndexCycle(double $latitude, double $longitude, int $radiusMin, int $radiusMax){
    $lat_offset_min = (double) $radiusMin/$this->real_scale;
    $lon_offset_min = (double) $radiusMin/calLandscapeScale($latitude);
    $lat_offset_max = (double) $radiusMax/$this->real_scale;
    $lon_offset_max = (double) $radiusMax/calLandscapeScale($latitude);
    $lat = (double) convertLat($latitude)/$this->latitude_scale;
    $lon = (double) convertLon($longitude)/$this->longitude_scale;
    $lat_min = (int)($lat - $lat_offset_max);
    $lat_max = (int)($lat + $lat_offset_max);
    $lon_min = (int)($lon - $lon_offset_max);
    $lon_max = (int)($lon + $lon_offset_max);

    $lat_min_min = (int)($lat - $lat_offset_min);
    $lat_min_max = (int)($lat + $lat_offset_min);
    $lon_min_min = (int)($lon - $lon_offset_min);
    $lon_min_max = (int)($lon + $lon_offset_min);

    $index_size = ($lat_max - $lat_min + 1) * ($lon_max - $lon_min + 1)
      - ($lat_min_max - $lat_min_min + 1) * ($lon_min_max - $lon_min_min + 1);
    $indexs = array();
    $i = 0;
    for(; $lat_min<=$lat_max; $lat_min++){
      for($l=$lon_min; $l<=$lon_max; $l++){
        if ($lat_min_min <= $lat_min && $lat_min <= $lat_min_max && $lon_min_min <= $l
          && $l <= $lon_min_max)
          continue;
        $indexs[$i] = ($lat_min<<16) ^ $l;

        $i++;
      }
    }
    return $indexs;
  }
  
  /*	
  public function main (){
    $lat=(double)23.131826;
    $lon=(double)113.28709430175786;
    $radius=(int) 3000;
    //echo $this->calIndexByLatLon($lat,$lon);
    //print_r($this->findIndex($lat,$lon,$radius));
    //echo pow($this->calDistanceSquare(23.131826,113.34360,23.142422880494955,113.28709430175786), 0.5);
  }

  //'36.456696', '115.985371', '398196736', '19330', '1381256066'
   */

  public function getAround($lon,$lat,$radius)
  {
    $degree = (24901 * 1609) / 360.0;
    $dpmLat = 1/$degree;
    $radiusLat = $dpmLat * $radius;
    $minLat = $lat - $radiusLat;
    $maxLat = $lat + $radiusLat;
    $mpdLng = $degree * cos($lat*(3.14159265/180));
    $dpmLng = 1 / $mpdLng;
    $radiusLng = $dpmLng * $radius;
    $minLng = $lon - $radiusLng;
    $maxLng = $lon + $radiusLng;

    return array($minLat,$minLng,$maxLat,$maxLng);

  }




  public function getDistance($lng1,$lat1,$lng2,$lat2)
  {

    $pi = 3.14159265;
    $earth_radius = 6378137;
    $rad = $pi / 180.0;

    $radLat1 = $lat1 * $rad;
    $radLat2 = $lat2 * $rad;

    $a = $radLat1 - $radLat2;
    $b = ($lng1 - $lng2)*$rad;
    $s = 2*asin(sqrt(pow(sin($a/2),2) + cos($radLat1) * cos($radLat2) * pow(sin($b/2),2)));
    $s = $s * $earth_radius;
    $s = round($s*10000)/10000;
    return $s;
  }	
}
