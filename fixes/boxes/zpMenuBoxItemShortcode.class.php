<?php

/**
 * Pricelist shortcode
 */
class zpMenuBoxItemShortcode extends ctShortcode implements ctVisualComposerShortcodeInterface {

    /**
     * Returns name
     * @return string|void
     */
    public function getName() {
        return 'Menu Box item Zonapro';
    }

    /**
     * Shortcode name
     * @return string
     */
    public function getShortcodeName() {
        return 'menu_box_item_zp';
    }

    /**
     * Returns shortcode type
     * @return mixed|string
     */

    public function getShortcodeType() {
        return self::TYPE_SHORTCODE_ENCLOSING;
    }

    /**
     * Handles shortcode
     * @param $atts
     * @param null $content
     * @return string
     */

    public function handle($atts, $content = null) {
        // var_dump($atts);
        extract(shortcode_atts($this->extractShortcodeAttributes($atts), $atts));

        $subprice = $subprice ? '<span>' . $subprice . '</span>' : '';
        $separator = ($separator == 'yes') ? '<hr>' : '';
        $postscript = ($postscript !== '') ? '<span class="info">'.$postscript.'</span>' : '';
        $thumb = $thumb == '' ? $image : $thumb;

        $imageHtml = '';
        if ($image != '') {
            $imageHtml = '<a class="pull-left" title="' . $title . '" href="' . $image . '" data-rel="prettyPhoto[' . ctMenuBoxShortcode::$gallery_id . ']">
            [rounded_img size="54" src="' . $thumb . '"][/rounded_img]
        </a>';
    }

    $mainContainerAtts = array(
        'class' => array('media', $class)
        );
        // var_dump($price);
    $html = '<div ' . $this->buildContainerAttributes($mainContainerAtts, $atts) . '>';
    $html .=$imageHtml;
    $html .='<div class="media-body">';
    $html .='<span class="title">' . $title . '</span><p>' . $content . '</p>';
    if($price!=''){
        $html.='<span class="price">';

        if(ct_get_option('products_currency_position', 'before_price') == 'before_price' || ct_get_option('products_currency_position', 'before_price')=='' ){
            $html.='<em>' . $currency . '</em>';
        }
        else{
            $html.= $price . $subprice;
        } 
        if(ct_get_option('products_currency_position', 'before_price') == 'before_price' || ct_get_option('products_currency_position', 'before_price')==''){
            $html.= $price . $subprice;
        }
        else{ 
            $html.='<em>' . $currency . '</em>';
        }
        $html.='</span>';
    }
    $html.=$postscript;
    $html.='</div>';
    $html.='</div>';
    $html.=$separator;

    return do_shortcode($html);
}

/**
     * Returns config
     * @return null
     */
public function getAttributes() {
    return array(
        'title' => array('label' => __('title', 'ct_theme'), 'default' => '', 'type' => 'input', 'help' => __('Every Menu Box Item should be wrapped in Menu Box element', 'ct_theme')),
        'image' => array('label' => __("image", 'ct_theme'), 'default' => '', 'type' => 'image', 'help' => __("Image source", 'ct_theme')),
        'thumb' => array('label' => __("thumbnail", 'ct_theme'), 'default' => '', 'type' => 'image', 'help' => __("Image thumbnail. If empty, image will be used instead.", 'ct_theme')),
        'content' => array('label' => __('content', 'ct_theme'), 'default' => '', 'type' => 'textarea'),
        'postscript' => array('label' => __('postscript', 'ct_theme'), 'default' => '', 'type' => 'input'),
        'price' => array('label' => __('price', 'ct_theme'), 'default' => '', 'type' => 'input', 'example' => '456,50'),
        'subprice' => array('label' => __('subprice', 'ct_theme'), 'default' => '', 'type' => 'input'),
        'currency' => array('label' => __('currency', 'ct_theme'), 'default' => ct_get_option('products_index_currency', '$'), 'type' => 'input'),
        'separator' => array('default' => 'yes', 'type' => 'select', 'options' => array('yes' => 'yes', 'no' => 'no'), 'label' => __('Add separator', 'ct_theme'), 'help' => __("add separator to item?", 'ct_theme')),
        'class' => array('label' => __("Custom class", 'ct_theme'), 'default' => '', 'type' => 'input', 'help' => __('Adding custom class allows you to set diverse styles in css to the element. Type in name of class, which you defined in css. You can add as much classes as you like.', 'ct_theme')),
        );

}

	/**
	 * Returns additional info about VC
	 * @return ctVisualComposerInfo
	 */
	public function getVisualComposerInfo() {
		return new ctVisualComposerInfo( $this, array(
          'icon' => 'fa-plus',
          'description'=> __('Add item to menu box', 'ct_theme'), ) );
	}
}

new zpMenuBoxItemShortcode();
