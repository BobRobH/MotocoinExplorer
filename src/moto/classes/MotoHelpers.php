<?php 
	class MotoHelpers {
		public static function getcoinsvolume($n) {
			if ($n==0) return 3 * 150000;
			
			if ($n<=8000) {
				return self::getcoinsvolume(0) + $n*100;
			}
			else {
				if ($n<1000000)                    return self::getcoinsvolume(8000)        + ($n-8000)       *20;
				if ($n>=200000*5  && $n<400000*5)  return self::getcoinsvolume(200000*5-1)  + ($n-200000*5+1) *10;
				if ($n>=400000*5  && $n<600000*5)  return self::getcoinsvolume(400000*5-1)  + ($n-400000*5+1) *5;
				if ($n>=600000*5  && $n<800000*5)  return self::getcoinsvolume(600000*5-1)  + ($n-600000*5+1) *2.5;
				if ($n>=800000*5  && $n<1000000*5) return self::getcoinsvolume(800000*5-1)  + ($n-800000*5+1) *1.25;
				if ($n>=1000000*5 && $n<1200000*5) return self::getcoinsvolume(1000000*5-1) + ($n-1000000*5+1)*0.625;
				if ($n>=1200000*5)                 return self::getcoinsvolume(1200000*5-1) + ($n-1200000*5+1)*0.4;				
			}
		}
	}
?>