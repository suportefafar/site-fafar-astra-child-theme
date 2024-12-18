<?php

add_shortcode( 'site_fafar_custom_sidebar_menu', 'site_fafar_custom_sidebar_menu' );

add_shortcode( 'site_fafar_highlight_area', 'site_fafar_highlight_area' );

add_shortcode( 'site_fafar_carousel', 'site_fafar_carousel' );


function site_fafar_custom_sidebar_menu() {
    

    // Get menu by user as default option
    $menu = get_menu_by_current_content();

    if ( is_home() ) {

        $menu = get_secondary_menu();

    }

    /* 
        Rendering the sidemenu with custom Menu Walker
    */

    echo wp_nav_menu(array(
        'menu'            => $menu,
        'depth'           => 2,
        'menu_class'      => 'm-0 list-group rounded-0',
        'item_classes'    => array( 'list-group-item','list-group-item-action','rounded-0','fafar-side-menu-item', 'd-flex', 'align-items-center' ),
        'walker'          => new FAFAR_Menu_Walker(),
    ));
}

/*
    Was defined that:
        The Primary Menu would be the header menu;
        The Secondary Menu would be the home page sidemenu;
    
    So...
*/

function get_institucional_menu() {

    $INSTITUCIONAL_MENU_ID = 4;

    return $INSTITUCIONAL_MENU_ID;

}

function get_secondary_menu() {
    /*
        Gets wherever menu was set as secondary to be
        shown at home page
    */
    
    $menu = array();
    $menu_locations = get_nav_menu_locations();
    $secondary_menu_location = 'secondary_menu';
    if ( isset( $menu_locations[ $secondary_menu_location ] ) ) {

        $secondary_menu_id = $menu_locations[ $secondary_menu_location ];
        $menu = wp_get_nav_menu_object( $secondary_menu_id );

    } else {
        // 'Secondary Menu' not found
        $menu = get_institucional_menu();
        
    }

    return $menu;
}

function get_menu_by_current_content() {

    $category = get_the_category();
    if( isset( $category[0] ) ) { // get_queried_object()->post_type == "post"
        
        return $category[0]->slug;

    } 

    if ( get_post_field( 'post_author' ) != "" ) { // get_queried_object()->post_type == "page"

        $post_author_id = get_post_field( 'post_author' );
        $user = get_user_by( 'id', $post_author_id );
        return $user->data->user_nicename;

    }
    
    return get_institucional_menu();

}


/*
 * Essa função é responsável por 
 * retornar a Área de Destaque na página principal
 */
function site_fafar_highlight_area() {

    if ( ! is_user_logged_in() ) return '';

    if ( have_posts() ) {

        query_posts('post_type=servicos&showposts=40&orderby=date&order=ASC');

        $itens = '';

        while ( have_posts() ) {

            the_post(); 
            
            $title       = the_title( $before = '', $after = '', $display = false );

            $guid        = get_the_guid();

            $url_servico = get_field('link_serviço');

            $id_img      = get_field('imagem_servico');
            $url_img     = wp_get_attachment_url($id_img);

            // print_r($servico);
            // print_r('<br/>');
            
            $itens .= 
                    '<div class="col col-sm-6 col-md-4 highlight-card">
                        <a href="' . $url_servico . '" target="_blank" title="Ir para ' . $title . '">
                            <img src="' . $url_img . '" alt="Logo da ' . $title . '" />
                        </a>
                    </div>';
        }

    }

    // return '
    // <div class="container w-100">
    //     <div class="row">
    //         <h6>Destaques</h6>
    //     </div>
    //     <div class="row">
    //         <div class="col col-sm-6 col-md-4 highlight-card">
    //             <a href="https://www.farmacia.ufmg.br/escutas/" target="_blank" title="Ir para Escuta Acadêmica">
    //                 <img src="https://www.farmacia.ufmg.br/wp-content/uploads/2024/12/escuta-fafar.png" alt="Logo da Escuta Acadêmica" />
    //             </a>
    //         </div>
    //         <div class="col col-sm-6 col-md-4 highlight-card">
    //             <a href="https://www.farmacia.ufmg.br/apresentacao-8/" target="_blank" title="Ir para Gerência Ambiental e Biossegurança">
    //                 <img src="https://www.farmacia.ufmg.br/wp-content/uploads/2024/12/gerenciamento-ambiental-biosseguranca.png" alt="Logo da Gerência Ambiental e Biossegurança" />
    //             </a>
    //         </div>
    //     </div>
    // </div>';

    return '
    <div class="container w-100">
        <div class="row">
            <h6>Serviços</h6>
        </div>
        <div class="row">
        ' . $itens . '
        </div>
    </div>';

}

function site_fafar_carousel() {

    // return '
    // <div id="carouselExample" class="carousel slide">
    //     <div class="carousel-inner">
    //         <div class="carousel-item active">
    //         <img src="https://pxl-catawbaedu.terminalfour.net/fit-in/740x740/prod01/channel_2/media/catawba-college/site-assets/images/hedrick-lawn-1-720X720.webp" class="d-block" style="height: 360px" alt="...">
    //         </div>
    //         <div class="carousel-item">
    //         <img src="https://pxl-catawbaedu.terminalfour.net/fit-in/740x740/prod01/channel_2/media/catawba-college/site-assets/images/hedrick-lawn-1-720X720.webp" class="d-block" style="height: 360px" alt="...">
    //         </div>
    //         <div class="carousel-item">
    //         <img src="https://pxl-catawbaedu.terminalfour.net/fit-in/740x740/prod01/channel_2/media/catawba-college/site-assets/images/hedrick-lawn-1-720X720.webp" class="d-block" style="height: 360px" alt="...">
    //         </div>
    //     </div>
    //     <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
    //         <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    //         <span class="visually-hidden">Previous</span>
    //     </button>
    //     <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
    //         <span class="carousel-control-next-icon" aria-hidden="true"></span>
    //         <span class="visually-hidden">Next</span>
    //     </button>
    // </div>
    // ';

    // return '
    //     <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
    //         <div class="carousel-inner">
    //             <div class="carousel-item active">
    //                 <img src="https://pxl-catawbaedu.terminalfour.net/fit-in/740x740/prod01/channel_2/media/catawba-college/site-assets/images/hedrick-lawn-1-720X720.webp" class="d-block" style="height: 360px" alt="...">
    //             </div>
    //             <div class="carousel-item">
    //                 <img src="https://ufmg.br/thumbor/o1fdi3yWuzQA-y02iIcN-qOfhHM=/0x0:952x600/952x600/https://ufmg.br/storage/a/1/e/7/a1e7d90d08c8fbd84b61cb10b92b316d_15983899508573_856776441.jpg" class="d-block" style="height: 360px" alt="...">
    //             </div>
    //             <div class="carousel-item">
    //                 <img src="https://dafarmaciaufmg.wordpress.com/wp-content/uploads/2011/11/cropped-faculdade-de-farmacia_imagelarge1.jpg" class="d-block" style="height: 360px" alt="...">
    //             </div>
    //         </div>
    //     </div>';

    // return '
    //     <div class="carousel-fafar">
    //         <img src="https://wallpapers.com/images/hd/macos-big-sur-1920-x-1080-background-uz3cwmapsoshabju.jpg" />
    //     </div>
    // ';

    return '';

}

