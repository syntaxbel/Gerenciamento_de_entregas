<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Login</title>
    <link rel="stylesheet" href="/src/styles/loginstyle.css">

    <script>


        async function entrar()
        {
            const vUsuario = document.getElementById("usuario").value;
            const vSenha   = document.getElementById("senha").value;

            if (vUsuario.trim() === "" || vSenha.trim() === "")
            {
                alert("Preencha todos os campos.");
                return;
            }

            const dados = new FormData();
            dados.append("pUsuario", vUsuario);
            dados.append("pSenha",   vSenha);
           

            try
            {
                const resposta = await fetch("/valida_login.php", {
                    method: "POST",
                    body:   dados
                });

                const data = await resposta.json();

                if (data.status === "success")
                {
                    window.location.href = data.redirect;
                }
                else
                {
                    alert(data.message);
                }
            }
            catch (erro)
            {
                console.error(erro);
                alert("Erro ao conectar com o servidor.");
            }
        }

        document.addEventListener("DOMContentLoaded", function()
        {
            document.getElementById("loginForm").addEventListener("submit", function(e)
            {
                e.preventDefault();
                entrar();
            });
        });

    </script>
</head>
<body>

    <main class="login-container">
        <section class="form-side">
            <h1>Faça o login</h1>

            <form id="loginForm">

                <div class="input-group">
                    <label>Usuário</label>
                    <input type="text" id="usuario" placeholder="Ex. maria.empresa" required />
                </div>

                <div class="input-group">
                    <label>Senha</label>
                    <input type="password" id="senha" placeholder="******" required />
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn-login">Entrar</button>
                </div>

            </form>
        </section>

        <section class="image-side">
            <img src="/assets/imagev2.jpg" alt="Desenho em 3D, van de entregas ao lado de homem segurando uma caixa">
        </section>
    </main>

</body>
</html>
