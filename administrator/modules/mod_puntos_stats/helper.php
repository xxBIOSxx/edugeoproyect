<?php


defined('_JEXEC') or die;

class modPuntosStatsHelper
{
    
    public static function getStats() {
		$all = self::countPuntos();
		$published = self::countPuntos('published = 1');
		$stats = array(
			'all' => $all,
			'published' => $published,
			'unpublished' => $all - $published
		);

		return $stats;
	}

   
    private static function countPuntos($where = null) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('COUNT(*) AS count')
			->from('#__puntos_marker');

		if($where) {
			$query->where($where);
		}

		$db->setQuery($query);

		return $db->loadObject()->count;
	}

  
    public static function tilesStats() {
        $path = JPATH_ROOT . '/media/com_Puntos/tiles';
        $totalSize = 0;
        $count = 0;
        foreach( new DirectoryIterator($path) as $file) {
            if($file->isFile()) {
                $totalSize += $file->getSize();
                $count += 1;
            }
        }

        return array(
            'files' => $count,
            'size' => $totalSize
        );

    }

    
    public static function formatBytes($size, $precision = 2)
    {
        if($size == 0) {
            return 0;
        }
        $base = log($size) / log(1024);
        $suffixes = array('', 'k', 'M', 'G', 'T');

        return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
    }

}