<?php 

if(!defined('ABSPATH'))
{
    die('Você não devia estar aqui');
}

use Carbon_Fields\Field;
use Carbon_Fields\Container;
use Carbon_Fields\Carbon_Fields;

add_action('after_setup_theme', 'load_carbon_fields');
add_action('carbon_fields_register_fields', 'create_options_page');

function load_carbon_fields()
{
    \Carbon_Fields\Carbon_Fields::boot();
}

function create_options_page()
{
    Container::make( 'theme_options', __( 'Formulário de Contato' ) )
        ->set_page_menu_position(30)
        ->set_icon('dashicons-media-text')
        ->add_fields( array(
            Field::make( 'checkbox', 'contact_plugin_active', __( 'Ativar' ) ),
            Field::make( 'text', 'contact_plugin_recipients', __( 'Emails Recebidos' ) )
                ->set_attribute( 'placeholder', 'seuemail@email.com' )
                ->set_help_text( 'O email que deseja submeter o email' ),
            Field::make( 'textarea', 'contact_plugin_message', __( 'Mensagem de Confirmação' ) )
                ->set_attribute( 'placeholder', 'Digite sua mensagem de confirmação' )
                ->set_help_text( 'Se deseja que a mensagem seja enviada escreva sua mensagem de confirmação' )
        ) );
}