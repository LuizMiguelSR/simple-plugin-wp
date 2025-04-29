
<?php if( get_plugin_options('contact_plugin_active') ):?>
<style>
    form {
        max-width: 600px;
        margin: 2rem auto;
        font-family: var(--wp--preset--font-family--system-font, sans-serif);
        padding: 1rem;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
    }

    form input[type="text"],
    form textarea {
        width: 100%;
        padding: 0.75rem;
        margin-bottom: 1rem;
        font-size: 1rem;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        background: #f9f9f9;
    }

    form textarea {
        min-height: 120px;
        resize: vertical;
    }

    form button {
        background-color: #000;
        color: #fff;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 4px;
        font-size: 1rem;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    form button:hover {
        background-color: #333;
    }

    .alert-box {
        max-width: 600px;
        margin: 1rem auto;
        padding: 1rem;
        border-radius: 6px;
        font-family: var(--wp--preset--font-family--system-font, sans-serif);
        font-size: 1rem;
        border-left: 4px solid;
    }

    .alert-success {
        background-color: #f0fdf4;
        color: #1a7f37;
        border-color: #1a7f37;
    }

    .alert-error {
        background-color: #fef2f2;
        color: #b91c1c;
        border-color: #b91c1c;
    }
</style>

<div id="form_sucess" class="alert-box alert-success" style="display: none;"></div>
<div id="form_error" class="alert-box alert-error" style="display: none;"></div>

<form id="enquiry_form">
    <?php wp_nonce_field('wp_rest'); ?>
    <input type="text" name="nome" placeholder="Nome" />
    <input type="text" name="email" placeholder="E-mail" />
    <input type="text" name="telefone" placeholder="Telefone" />
    <textarea name="mensagem" placeholder="Mensagem"></textarea>

    <button type="submit">Enviar Formulário</button>
</form>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById("enquiry_form");
        const successDiv = document.getElementById("form_sucess");
        const errorDiv = document.getElementById("form_error");

        if (form) {
            form.addEventListener("submit", function (e) {
                e.preventDefault();
                const formData = new FormData(form);
                const data = new URLSearchParams(formData);

                // Oculta e limpa mensagens anteriores
                successDiv.style.display = "none";
                errorDiv.style.display = "none";
                successDiv.innerHTML = "";
                errorDiv.innerHTML = "";

                fetch("<?php echo get_rest_url(null, 'v1/contact-form/submit'); ?>", {
                    method: "POST",
                    body: data
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Erro na resposta da API");
                    }
                    return response.json();
                })
                .then(result => {
                    form.style.display = "none";
                    successDiv.innerHTML = typeof result === 'string' ? result : JSON.stringify(result);
                    successDiv.style.display = "block";
                })
                .catch(error => {
                    errorDiv.innerHTML = "Erro ao enviar o formulário. Tente novamente.";
                    errorDiv.style.display = "block";
                    console.error(error);
                });
            });
        }
    });
</script>

<?php else:?>

<p>Este formulário não está ativado</p>

<?php endif;?>