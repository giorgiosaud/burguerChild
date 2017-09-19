<?php

/**
 * Draws products
 */
class zpProductsShortcode extends ctShortcodeQueryable implements ctVisualComposerShortcodeInterface
{

    /**
     * Returns name
     * @return string|void
     */
    public function getName()
    {
        return 'Products Zonapro';
    }

    /**
     * Shortcode name
     * @return string
     */
    public function getShortcodeName()
    {
        return 'zp_products';
    }


    /**
     * Handles shortcode
     * @param $atts
     * @param null $content
     * @return string
     */

    public function handle($atts, $content = null)
    {
        $attributes = shortcode_atts($this->extractShortcodeAttributes($atts), $atts);
        $rounded = '';
        extract($attributes);


        $products = $this->getCollection($attributes, array('post_type' => 'ct_product'));
        var_dump($products);
        $counter = 0;

        $productBoxHtml = '';
        $qtyProds=(count($products));
        foreach ($products as $p) {
            $custom = get_post_custom($p->ID);
            $currencyPerProd = (isset($custom["currency"][0])) ? $custom["currency"][0] : ct_get_option('products_index_currency', '$');

            $counter++;
            if ($counter == 1) {
                $productBoxHtml .= '[row]';
            }
            $align = '';
            if ($counter == 1) {
                $align = 'left';
            } elseif ($counter == 3) {
                $align = 'right';
            }
            switch ($qtyProds) {
                case 1:
                    $productBoxHtml .= '[full_column]';
                    break;
                case 2:
                    $productBoxHtml .= '[half_column]';  
                    break;
                default:
                    $productBoxHtml .= '[third_column sm="6"]';   
                    break;
            }
            //forward params
            $productBoxHtml .= $this->embedShortcode('zp_product', array_merge($attributes, array('id' => $p->ID, 'align' => $align, 'style' => 3, 'rounded' => $rounded, 'currency' => $currencyPerProd)));
            switch ($qtyProds) {
                case 1:
                    $productBoxHtml .= '[/full_column]';
                    break;
                case 2:
                    $productBoxHtml .= '[/half_column]';  
                    break;
                default:
                    $productBoxHtml .= '[/third_column]';   
                    break;
            }
            
            if ($counter == 3 || $counter == count($products)) {
                $counter = 0;
                $productBoxHtml .= '[/row]';
            }
        }

        return do_shortcode($productBoxHtml);
    }


    /**
     * Returns params from array ($custom)
     * @param $arr
     * @param $key
     * @param int $index
     * @param string $default
     * @return bool
     */

    protected function getFromArray($arr, $key, $index = 0, $default = '')
    {
        return isset($arr[$key][$index]) ? $arr[$key][$index] : $default;;
    }

    /**
     * Shortcode type
     * @return string
     */
    public function getShortcodeType()
    {
        return self::TYPE_SHORTCODE_SELF_CLOSING;
    }

    /**
     * Returns config
     * @return null
     */
    public function getAttributes()
    {
        $atts = $this->getAttributesWithQuery(array(
            'cat_name' => array('query_map' => 'category_name', 'default' => '', 'type' => 'input', 'label' => __("Category name", 'ct_theme'), 'help' => __("Name of category to filter", 'ct_theme')),
            'tag' => array('default' => '', 'type' => 'input', 'label' => __("Tag name (slug)", 'ct_theme'), 'help' => __("Comma separated values: tag1,tag2 To exclude tags use '-' minus: -mytag will exclude tag 'mytag'", 'ct_theme')),
            'currency_per_prod' => array('label' => __('individual currency?', 'ct_theme'), 'default' => 'no', 'type' => 'select', 'choices' => array('yes' => __('yes', 'ct_theme'), 'no' => __('no', 'ct_theme'))),
            'limit' => array('label' => __('limit', 'ct_theme'), 'default' => 4, 'type' => 'input', 'help' => __("Number of elements", 'ct_theme')),
            'above_price_text' => array('label' => __("Above price text", 'ct_theme'), 'default' => 'just', 'type' => 'input', 'help' => __("Word above the price", 'ct_theme')),
            'images' => array('label' => __('images', 'ct_theme'), 'default' => 'yes', 'type' => 'select', 'choices' => array('yes' => __('yes', 'ct_theme'), 'no' => __('no', 'ct_theme')), 'help' => __("Show images?", 'ct_theme')),
            'use_thumbnail' => array('label' => __('Use thumbnail image', 'ct_theme'), 'default' => 'yes', 'type' => 'select', 'options' => array(
                'yes' => 'yes',
                'no' => 'no',
            ), 'help' => __('Use thumbnail image instead of product one?', 'ct_theme')),
            'rounded' => array('label' => __('Rounded ?', 'ct_theme'), 'default' => 'no', 'type' => 'select', 'options' => array(
                'yes' => 'yes',
                'no' => 'no',
            )),
            'showprice' => array('label' => __('Show price ?', 'ct_theme'), 'default' => 'yes', 'type' => 'select', 'options' => array(
                'yes' => 'yes',
                'no' => 'no',
            )),
        ));

        //if (isset($atts['cat'])) {
            //unset($atts['cat']);
        //}
        return $atts;
    }

	/**
	 * Returns additional info about VC
	 * @return ctVisualComposerInfo
	 */
	public function getVisualComposerInfo() {
		return new ctVisualComposerInfo( $this, array(
		    'icon' => 'fa-cubes',
            'description'=>__("Displayssss products on page", 'open-burguer'),
        ) );
	}
}


new zpProductsShortcode();