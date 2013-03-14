<?php
class Entity extends fActiveRecord {


    /**
     *
     * Returns the html code to the entity image.<br>
     * If no images was found it will return the default image.
     *
     * @param String $tp_name
     *
     * @param int    $size
     *
     * @param String $classes
     *
     * @return string
     */
    public static function getEntityImg($tp_name, $size = 32, $classes = null) {
        $path = __ROOT__ . 'media/img/entities/';
        $img = $path . $tp_name . '.png';

        if(!file_exists($img))
            $img = $path . 'none.png';

        if(!is_null($classes))
            $class = 'class="' . $classes . '"';
        else
            $class = '';

        return
            '<img ' . $class . ' src="' . fFilesystem::translateToWebPath($img) . '" title="' .
            fText::compose($tp_name) . '" alt="' .
            fText::compose($tp_name) . '" style="width: ' . $size . 'px; height: ' . $size . 'px">';
    }

    /**
     * Gets the most dangerous entity.<br>
     * The first array value is an fNumber which is the count. The second one is the entity name.
     *
     * @return array
     */
    public static function getMostDangerous() {
        $res = fORMDatabase::retrieve()->translatedQuery('
                    SELECT SUM(pve.player_killed) AS total, e.tp_name
                    FROM "prefix_total_pve_kills" pve, "prefix_entities" e
                    WHERE pve.entity_id = e.entity_id
                    GROUP BY pve.entity_id
                    ORDER BY SUM(pve.player_killed) DESC
                    LIMIT 0,1
        ');

        try {
            $row = $res->fetchRow();
            $num = new fNumber($row['total']);

            return array($num->format(), $row['tp_name']);
        } catch(fNoRowsException $e) {
            return array(0, 'none');
        }
    }

    /**
     * Gets the most killed entity.<br>
     * The first array value is an fNumber which is the count. The second one is the entity name.
     *
     * @return array
     */
    public static function getMostKilled() {
        $res = fORMDatabase::retrieve()->translatedQuery('
                    SELECT SUM(pve.creature_killed) AS total, e.tp_name
                    FROM "prefix_total_pve_kills" pve, "prefix_entities" e
                    WHERE pve.entity_id = e.entity_id
                    GROUP BY pve.entity_id
                    ORDER BY SUM(pve.creature_killed) DESC
                    LIMIT 0,1
        ');

        try {
            $row = $res->fetchRow();
            $num = new fNumber($row['total']);

            return array($num->format(), $row['tp_name']);
        } catch(fNoRowsException $e) {
            return array(0, 'none');
        }
    }

    /**
     * Returns the html code to the entity.<br>
     * If no image was found it will return the default image.
     *
     * @param int    $size
     * @param String $classes
     *
     * @return string
     */
    public function getImage($size = 32, $classes = null) {
        return Entity::getEntityImg($this->getTpName(), $size, $classes);
    }

    /**
     * Returns the translated entity name.
     *
     * @return string
     */
    public function getName() {
        return fText::compose($this->getTpName());
    }

    /**
     * Returns the translated encoded entity name.
     *
     * @return string
     */
    public function encodeName() {
        return fHTML::encode($this->getName());
    }

    /**
     * Returns the translated prepared entity name.
     *
     * @return string
     */
    public function prepareName() {
        return fHTML::encode($this->getName());
    }

}
