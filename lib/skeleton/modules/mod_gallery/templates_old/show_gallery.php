<?
    $db =& dbObject::globalValue('database');
    $imgAr = array();
    $str = '';
    // extract various gallery info from database
    list ($gallerytype, $mode, $popup, $showtitles) = explode('#', $this->field->DataMixed);
    if ($popup == 'ja') $ele = 'BlestBoxContent';
    else $ele = 'Bildegalleri';
    
    // initializations based on gallery type: thumbs or slides
    if ($gallerytype == 'thumbs')
    {
        $galheight = ((int)(.75 * $this->width));
        list ($cols, $rows) = explode('_', $mode);
        $thumbs_per_page = $cols * $rows;
        if(!$this->pos) $this->pos = 0;
        $q = 'SELECT * FROM GalleryItem WHERE ContentElementID = ' . $this->content->ID . ' LIMIT ' . $this->pos . ', ' . $thumbs_per_page;
        $q_tot = 'SELECT COUNT(*) FROM GalleryItem WHERE ContentElementID = ' . $this->content->ID;
        list($total, ) = $db->fetchRow($q_tot);
    }
    else if ($gallerytype == 'slides')
    {
        $this->width = min($this->width - 24, 512);
        $galheight = ((int)(.75 * $this->width));
        $q = 'SELECT * FROM GalleryItem WHERE ContentElementID = ' . $this->content->ID;
    }

    // generate html code for gallery, based on gallery type
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
                $str .= '<div id="GalleryImages" style="margin: 8px auto 0px auto; width: ' . $this->width . 'px; min-height: '. $galheight .'px;">';
                for ($i = 0; $i <= $rows - 1; $i++)
                {
                    $str .= '<div class="ClearLeft">';
                    for ($j = 0; $j <= $cols - 1; $j++)
                    {
                        $img = $imgAr[($i * $rows) + $j];
                        $i1 = (int)($this->width/$cols);
                        $i2 = (int)(.75 * $this->width)/$rows;
                        $str .= '<div id="thumb' . $img->ID . '" class="FloatLeft" style="margin: 4px 4px;"><a onclick="showImage('. $img->ID . ', ' .
                        $this->width . ');"><img src="' . $img->getImageUrl(($i1) - 16, ($i2) - 16, 'centered', '', 0xffffff) . '"/></a>';
                        if ($showtitles == 'ja') $str .= '<div class="ImgTitle">'. $img->Title .'</div>';
                        $str .= '</div>';

                        if (($i * $rows) + $j == sizeof($imgAr) - 1) break;
                    }
                    $str .= '</div>';
                    if (($i * $rows) + $j == sizeof($imgAr) - 1) break;
                }
                $str .= '</div>';
                if($total > $thumbs_per_page)
                {
                    $str .= '<div id="Navigation">';

                    if(($this->pos) > 0)
                    {
                        $str .= '<div id="Forrige" style="float: left; margin-left: 16px;"><a href="javascript:nav(\\\'' .
                        $ele . '\\\',' . ($this->pos - $thumbs_per_page) . ',0)">Forrige</a></div>';
                    }
                    
                    if(($this->pos + $thumbs_per_page) < $total)
                    {
                        $str .= '<div id="Neste" style="float: right; margin-right: 16px;"><a href="javascript:nav(\\\'' .
                        $ele . '\\\',' . ($this->pos + $thumbs_per_page) . ',0)">Neste</a></div>';
                    }
                    $str .= '</div>';
                }                
                break;
                        
            case 'slides':
                $img = $imgAr[0];
                $str .= '<div id="BigImgContainer" style="width: ' . $this->width . 'px; margin: auto;">';
                $str .= '<div id="BigGalleryImg"><img src="' . $img->getImageUrl($this->width, $galheight, 'centered', '', 0xffffff) . '"/></div></div>';
                $str .= '<div id="GalleryImages" style="white-space: nowrap; overflow: hidden; position: relative; height: 64px; width:' . $this->width . 'px; margin: auto;">';
                $str .= '<div id="ThumbContainer" style="overflow: hidden; height: 96px; position: absolute; left: -100px;">';
                foreach($imgAr as $img)
                {
                    $str .= '<img src="' . $img->getImageUrl(128, 64, 'centered', '', 0xffffff) . '" style="margin-right: 4px;" onclick="changeSlide(' . $this->width . ', ' . $galheight . ', ' . $img->ID . ')"/>';
                }
                
                $str .= '</div></div>';
                break;
            
            default:
                die("Mangler info om gallerimodus");
        }
    }
    return $str;
?>
