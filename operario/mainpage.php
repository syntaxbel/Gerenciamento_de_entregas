
        
        <aside class="sidebar">
            <button class="btn-icone" onclick="abrirModal()" title="Adicionar Nova Entrega">
                <img src="/assets/icon_mais.png" alt="Adicionar" class="icon-adicionar">
            </button>

            <button class="btn-icone" id="btnEditarSidebar" title="Editar Selecionado">
                <img src="/assets/icon_edicao.png" alt="Editar" class="icon-editar">
            </button>

        </aside>

        <main class="main-content">
            <div class="dashboard-card">
                <h2>Gerenciamento de Entregas</h2>

                <div class="tabela-responsiva">
                    <table>
                        <thead>
                            <tr>
                                <th>Transportadora</th>
                                <th>ID Pedido</th>
                                <th>Data</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="tabela-corpo">
                            <tr>
                                <td colspan="4" style="text-align: center; color: #A3AED0; padding: 40px;">
                                    Nenhuma entrega cadastrada.
                                </td>
                             </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div> <div class="modal-overlay" id="modalAdicionar">
        <div class="modal-box">
            <h3>Nova Entrega</h3>
            
            <form id="formEntrega">
                <div class="form-group">
                    <label>Empresa Transportadora</label>
                    <input type="text" id="inputNome" placeholder="Ex: Loggi, Sedex..." required>
                </div>
                <div class="form-group">
                    <label>ID do Pedido</label>
                    <input type="text" id="inputID" placeholder="Ex: RCS-99" required>
                </div>
                <div class="form-group">
                    <label>Data</label>
                    <input type="date" id="inputData" required>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select id="inputStatus">
                        <option value="Embalado">Embalado</option>
                        <option value="Transportando">Transportando</option>
                        <option value="Entregado">Entregue</option>
                        <option value="Interrompido">Interrompido</option>
                    </select>
                </div>

                <div class="modal-buttons">
                    <button type="button" class="btn-cancel" onclick="fecharModal()">Cancelar</button>
                    <button type="submit" class="btn-confirm">Salvar Entrega</button>
                </div>
            </form>
        </div>
    </div>





    <script src="/operario/mainpage.js"></script>
</body>
</html>
