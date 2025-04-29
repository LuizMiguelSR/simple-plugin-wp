<?php 

if(!defined('ABSPATH'))
{
    die('Você não devia estar aqui');
}

add_shortcode('contact', 'show_contact_form');
add_action('rest_api_init', 'create_rest_endpoint');
add_action('init', 'create_submissions_page');
add_action('add_meta_boxes', 'create_meta_box');
add_filter('manage_submission_posts_columns', 'custom_submission_columns');
add_action('manage_submission_posts_custom_column', 'fill_submission_columns', 10, 2);
add_action('admin_init', 'setup_search');

function setup_search()
{
    global $typenow;

    if($typenow === 'submission')
    {
        add_filter('posts_search', 'submission_search_override', 10, 2);
    }
}

function submission_search_override($search, $query)
{
    global $wpdb;

    if ($query->is_main_query() && !empty($query->query['s'])) {
        $sql    = "
          or exists (
              select * from {$wpdb->postmeta} where post_id={$wpdb->posts}.ID
              and meta_key in ('name','email','telefone', 'mensagem')
              and meta_value like %s
          )
      ";
        $like   = '%' . $wpdb->esc_like($query->query['s']) . '%';
        $search = preg_replace(
              "#\({$wpdb->posts}.post_title LIKE [^)]+\)\K#",
              $wpdb->prepare($sql, $like),
              $search
        );
  }

  return $search;
}

function fill_submission_columns($column, $post_id)
{
    switch($column)
    {
        case 'name':
            echo esc_html(get_post_meta($post_id, 'nome', true));
        break;

        case 'email':
            echo esc_html(get_post_meta($post_id, 'email', true));
        break;

        case 'phone':
            echo esc_html(get_post_meta($post_id, 'telefone', true));
        break;

        case 'message':
            echo esc_html(get_post_meta($post_id, 'mensagem', true));
        break;
    }
}

function custom_submission_columns($columns)
{
    $columns = array(
        'cb' => $columns['cb'],
        'name' => __('Nome', 'contact-plugin'),
        'email' => __('E-mail', 'contact-plugin'),
        'phone' => __('Telefone', 'contact-plugin'),
        'message' => __('Mensagem', 'contact-plugin'),
    );
    return $columns;
}

function create_meta_box()
{
    add_meta_box('custom_contact_form', 'Submissão', 'display_submission', 'submission');
}

function display_submission()
{
    $post_metas = get_post_meta(get_the_ID());
    unset($post_metas['_edit_lock']);

    echo '<ul>';

    foreach ($post_metas as $key => $value) {
        echo '<li><strong>' . ucfirst($key) . '</strong>: ' . $value[0] . '</li>';
    }

    echo '<ul>';
}

function create_submissions_page()
{
    $args = [
        'public' => true,
        'has_archive' => true,
        'menu_position' => 30,
        'publicly_queriable' => false,
        'labels' => [
            'name' => 'Submissões',
            'singular_name' => 'Submissão',
            'edit_item' => 'Ver submissão',
        ],
        'supports' => false,
        'capability_type' => 'post',
        'capabilities' => array(
            'create_posts' => false,
        ),
        'map_meta_cap' => true,
    ];

    register_post_type('submission', $args);
}

function show_contact_form()
{
    ob_start();
    include MY_PLUGIN_PATH . '/includes/templates/contact-form.php';
    return ob_get_clean();
}

function create_rest_endpoint()
{
    register_rest_route('v1/contact-form','submit', array(
        'methods' => 'POST',
        'callback' => 'handle_enquiry'
    ));
}

function handle_enquiry($data)
{
    $params = $data->get_params();
    $field_name = sanitize_text_field($params['nome']);
    $field_email = sanitize_email($params['email']);
    $field_phone = sanitize_text_field($params['telefone']);
    $field_message = sanitize_textarea_field($params['mensagem']);
    
    if(!wp_verify_nonce($params['_wpnonce'], 'wp_rest'))
    {
        return new WP_REST_Response('Mensagem não enviada', 422);
    }
    unset($params['_wpnonce']);
    unset($params['_wp_http_referer']);
    // Enviando a mensagem de email
    $headers = [];

    $admin_email = get_bloginfo('admin_email');
    $admin_name = get_bloginfo('name');

    $recipient_email = get_plugin_options('contact_plugin_recipients');

    if (!$recipient_email) {
        $recipient_email = strtolower(trim($recipient_email));
    } else {
        $recipient_email = $admin_email;
    }

    $headers[] = "From: {$admin_name} <{$admin_email}>";
    $headers[] = "Reply-to: {$field_name} <{$field_email}>";
    $headers[] = "Content-Type: text/html";

    $subject = "Novo formulário de {$field_name}";
    
    $message = '';
    $message .= "<h1> Mensagem enviada por {$field_name}</h1> <br /><br />";

    $postar = [
        'post_title' => $field_name,
        'post_type' => 'submission',
        'post_status' => 'publish'
    ];

    $post_id = wp_insert_post($postar);

    foreach($params as $label => $value)
    {
        switch ($label) {

              case 'mensagem':

                    $value = sanitize_textarea_field($value);
                    break;

              case 'email':

                    $value = sanitize_email($value);
                    break;

              default:

                    $value = sanitize_text_field($value);
        }
        add_post_meta($post_id, sanitize_text_field($label), $value);

        $message .= '<strong>' . sanitize_text_field(ucfirst($label)) . ':</strong> ' . $value . '<br />';
    }

    wp_mail($admin_email, $subject, $message, $headers);
    $confirmation_message = "Sua mensagem foi enviada com sucesso!";

    if (get_plugin_options('contact_plugin_message')) {

          $confirmation_message = get_plugin_options('contact_plugin_message');

          $confirmation_message = str_replace('{name}', $field_name, $confirmation_message);
    }

    return new WP_Rest_Response($confirmation_message, 200);
}