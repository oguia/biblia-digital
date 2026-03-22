<?php
/**
 * Guia Afiliados Theme Functions and Definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Guia_Afiliados_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Enqueue scripts and styles.
 */
function guia_afiliados_enqueue_styles() {
    // Enfileirar estilos do tema pai (Hello Elementor)
    wp_enqueue_style(
        'hello-elementor-parent-style',
        get_template_directory_uri() . '/style.min.css'
    );

    // Enfileirar Google Fonts: Playfair Display (Títulos Premium) e Open Sans (Corpo de Texto)
    wp_enqueue_style(
        'guia-afiliados-fonts',
        'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Open+Sans:wght@400;600&display=swap',
        array(),
        null
    );

    // Enfileirar estilos do tema filho com prioridade mais alta, dependendo do elementor para carregar DEPOIS dele
    // Verifica se o Elementor está ativo para adicioná-lo como dependência, forçando nosso CSS a carregar por último
    $dependencies = array('hello-elementor-parent-style');
    if ( wp_style_is( 'elementor-frontend', 'registered' ) ) {
        $dependencies[] = 'elementor-frontend';
    }
    if ( wp_style_is( 'hello-elementor-theme-style', 'registered' ) ) {
        $dependencies[] = 'hello-elementor-theme-style';
    }

    $theme_version = wp_get_theme()->get('Version');
    $css_file = get_stylesheet_directory() . '/style.css';
    $version = file_exists($css_file) ? $theme_version . '.' . filemtime($css_file) : $theme_version;

    wp_enqueue_style(
        'guia-afiliados-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        $dependencies,
        $version
    );
}
// Prioridade 99 para garantir que rode depois do Elementor (padrão é 10)
add_action( 'wp_enqueue_scripts', 'guia_afiliados_enqueue_styles', 99 );

/**
 * Adicionar suporte ao WooCommerce (Garante que a galeria e as customizações funcionem no Elementor)
 */
function guia_afiliados_add_woocommerce_support() {
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'guia_afiliados_add_woocommerce_support' );

/**
 * Injetar o Selo "Escolha do Guia" nos produtos do WooCommerce marcados como "Destaque" (Featured)
 */
function guia_afiliados_add_featured_badge() {
    global $product;

    // Se o produto não for objeto válido, retorna
    if ( ! is_a( $product, 'WC_Product' ) ) {
        return;
    }

    // Verifica se o produto está marcado como destaque (estrela preenchida no admin do Woo)
    if ( $product->is_featured() ) {
        // Ícone SVG de Estrela/Pin minimalista
        $svg_icon = '<svg xmlns="http://www.w0.org/2000/svg" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>';

        echo '<div class="selo-escolha-guia" title="Recomendado pelo Curador Urbano">';
        echo $svg_icon . ' Escolha do Guia';
        echo '</div>';
    }
}
// Adicionar a ação antes de renderizar o título do produto no grid (loop)
add_action( 'woocommerce_before_shop_loop_item_title', 'guia_afiliados_add_featured_badge', 10 );

/**
 * Personalizar o texto do botão de Produto Externo/Afiliado na página do Produto (Single Product)
 * Isso assegura que o botão tenha a mesma chamada à ação (CTA) se não configurado no produto.
 */
function guia_afiliados_custom_external_button_text( $text, $product ) {
    if ( $product->is_type( 'external' ) ) {
        // Se o texto original não estiver vazio, usar ele. Caso contrário, um padrão agressivo:
        $button_text = $product->get_button_text();
        if ( empty( $button_text ) ) {
            return __( 'Ver Oferta', 'guia-afiliados-theme' );
        }
    }
    return $text;
}
add_filter( 'woocommerce_product_single_add_to_cart_text', 'guia_afiliados_custom_external_button_text', 10, 2 );

/**
 * Personalizar o texto do botão no Grid (Shop Page / Archive)
 */
function guia_afiliados_custom_archive_external_button_text( $text, $product ) {
    if ( $product->is_type( 'external' ) ) {
        $button_text = $product->get_button_text();
        if ( empty( $button_text ) ) {
            return __( 'Ver Oferta', 'guia-afiliados-theme' );
        }
    }
    return $text;
}
add_filter( 'woocommerce_product_add_to_cart_text', 'guia_afiliados_custom_archive_external_button_text', 10, 2 );

/**
 * Alterar a ordem dos elementos no Loop de Produtos do WooCommerce
 * Para alinhar com o design de Grid estilo ML, onde a imagem é limpa no topo e o preço fica antes do título, ou conforme o CSS.
 * O CSS já resolve o visual principal, mas removemos as avaliações (ratings) do grid para ficar mais "limpo".
 */
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

// Remover a estrutura padrão de link de encapsulamento para colocar o botão no final
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
// Readicionar o botão num lugar que garanta que ele vai para o final do card
add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

?>