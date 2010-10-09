
<?
    list($gallerytype, $mode, $popup) = explode('#', $this->field->DataMixed);
    list($cols, $rows) = explode('_', $mode);
    $thumbs_per_page = $cols * $rows;
    if(!$this->pos) $this->pos = 0;
    $db =& dbObject::globalValue('database');

    $q = 'SELECT * FROM GalleryItem WHERE ContentElementID = ' . $this->content->ID . ' LIMIT ' . $this->pos . ', ' . $thumbs_per_page;
    $q_tot = 'SELECT COUNT(*) FROM GalleryItem WHERE ContentElementID = ' . $this->content->ID;
    list($total, ) = $db->fetchRow($q_tot);
    $imgAr = array();
    $galheight = ((int)(.75 * $this->width));
    $str = '';
    if ($results = $db->fetchObjectRows($q))
    {
        foreach ($results as $result)
        {
            $image = new dbImage($result->ImageID);
            array_push($imgAr, $image);
        }
        switch ($gallerytype)
        {
            case 'thumbs':
                $str .= '<div id="BigGalleryImg"></div>';
                $str .= '<div id="GalleryImages" style="min-height: '. $galheight .'px;">';
                for ($i = 0; $i <= $rows - 1; $i++)
                {
                    $str .= '<div class="ClearLeft">';
                    for ($j = 0; $j <= $cols - 1; $j++)
                    {
                        $img = $imgAr[($i * $rows) + $j];
                        $i1 = (int)($this->width/$cols);
                        $i2 = (int)(.75 * $this->width)/$rows;
                        $str .= '<div class="FloatLeft" style="margin: 4px 4px;"><a onclick="showImage('. $img->ID . ');"><img src="' . $img->getImageUrl(($i1) - 16, ($i2) - 16, 'centered', '', 0xffffff) . '"/></a></div>';

                        if (($i * $rows) + $j == sizeof($imgAr) - 1) break;
                    }
                    $str .= '</div>';
                    if (($i * $rows) + $j == sizeof($imgAr) - 1) break;
                }
                if($total > $thumbs_per_page)
                {
                    $str .= '<div id="Navigation">';

                    if(($this->pos) > 0)
                    {
                        $str .= '<div id="Forrige" style="float: left; margin-left: 16px;"><a href="javascript:nav(\\\'GalleryImages\\\',' . ($this->pos - $thumbs_per_page) . ',0)">Forrige</a></div>';
                    }
                    
                    if(($this->pos + $thumbs_per_page) < $total)
                    {
                        $str .= '<div id="Neste" style="float: right; margin-right: 16px;"><a href="javascript:nav(\\\'GalleryImages\\\',' . ($this->pos + $thumbs_per_page) . ',0)">Neste</a></div>';
                    }
                    $str .= '</div></div>';
                }
                break;
                        
            case 'slides':
                break;
            
            default:
                die("Mangler info om gallerimodus");
        }
    }
    return $str;
?>

