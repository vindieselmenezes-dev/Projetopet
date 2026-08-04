<aside class="sidebar">

    <nav>

        <ul>

            <li><a href="dashboard.php">🏠 Dashboard</a></li>

            <li><a href="cadastrar_pet.php">🐶 Cadastrar Pet</a></li>

            <li><a href="meus_pets.php">📋 Meus Pets</a></li>

            <li><a href="meus_favoritos.php">⭐ Meus Favoritos</a></li>

            <li><a href="cadastrar_empresa.php">🏢 Cadastrar Empresa</a></li>

            <li><a href="minhas_empresas.php">🏬 Minhas Empresas</a></li>

            <?php if (isset($_SESSION['perfil_tipo']) && $_SESSION['perfil_tipo'] === 'administrador'): ?>
                <li><a href="admin_usuarios.php">👥 Usuários</a></li>
                <li><a href="admin_pets.php">🐾 Pets</a></li>
            <?php endif; ?>

            <li><a href="pets_perdidos.php">🔍 Pets Perdidos</a></li>

            <li><a href="pets_encontrados.php">❤️ Pets Encontrados</a></li>

            <li><a href="pets_adocao.php">🏠 Para Adoção</a></li>

            <li><a href="pets_tutor.php">🏡 Com Tutor</a></li>

            <li><a href="pets_adotados.php">🎉 Adotados</a></li>

            <li><a href="endereco.php">👤 Meu Perfil</a></li>

            <li><a href="alterar_senha.php">🔒 Alterar Senha</a></li>

            <li><a href="logout.php">🚪 Sair</a></li>

        </ul>

    </nav>

</aside>
