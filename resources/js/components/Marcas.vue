<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <!-- Início do card de busca -->
        <card-component titulo="Busca de marcas">
          <template v-slot:conteudo>
            <div class="row">
              <div class="col mb-3">
                <input-container-component
                  titulo="ID"
                  id="inputId"
                  id-help="idHelp"
                  texto-opcional="Opcional. Informe o ID do registro"
                >
                  <input
                    type="number"
                    class="form-control"
                    id="inputId"
                    aria-describedby="idHelp"
                    placeholder="ID"
                    v-model="busca.id"
                  />
                </input-container-component>
              </div>
              <div class="col mb-3">
                <input-container-component
                  titulo="Nome"
                  id="inputNome"
                  id-help="nomeHelp"
                  texto-opcional="Opcional. Informe o nome da marca"
                >
                  <input
                    type="text"
                    class="form-control"
                    id="inputNome"
                    aria-describedby="nomeHelp"
                    placeholder="Nome da marca"
                    v-model="busca.nome"
                  />
                </input-container-component>
              </div>
            </div>
          </template>
          <template v-slot:rodape>
            <button
              type="submit"
              class="btn btn-primary btn-sm"
              @click="pesquisar()"
            >
              Pesquisar
            </button>
          </template>
        </card-component>
        <!-- Fim do card de busca -->

        <!--Início do card de alerta de transição-->
        <alert-component
          v-if="$store.state.transacao.status == 'sucesso'"
          tipo="success"
          titulo="Transação realizada com sucesso"
          :detalhes="{ mensagem: $store.state.transacao.mensagem }"
        ></alert-component>
        <alert-component
          v-if="$store.state.transacao.status == 'erro'"
          tipo="danger"
          titulo="Erro na transação"
          :detalhes="{ mensagem: $store.state.transacao.mensagem }"
        ></alert-component>
        <!--Fim do card de alerta de transição-->

        <!-- Início do card de listagem de marcas -->
        <card-component titulo="Relação de marcas">
          <template v-slot:conteudo>
            <table-component
              :dados="marcas.data"
              :visualizar="{
                visivel: true,
                dataBsToggle: 'modal',
                dataBsTarget: '#modalMarcaVisualizar',
              }"
              :atualizar="true"
              :remover="{
                visivel: true,
                dataBsToggle: 'modal',
                dataBsTarget: '#modalMarcaRemover',
              }"
              :titulos="{
                id: { titulo: 'ID', tipo: 'texto' },
                nome: { titulo: 'Nome', tipo: 'texto' },
                imagem: { titulo: 'Imagem', tipo: 'imagem' },
                created_at: { titulo: 'Data de criação', tipo: 'data' },
              }"
            >
            </table-component>
          </template>
          <template v-slot:rodape>
            <div class="row">
              <div class="col-10">
                <pagination-component>
                  <li
                    v-for="(item, key) in marcas.links"
                    :key="key"
                    :class="item.active ? 'page-item active' : 'page-item'"
                    @click="paginacao(item)"
                  >
                    <a class="page-link text-nowrap" v-html="item.label"></a>
                  </li>
                </pagination-component>
              </div>
            </div>
            <button
              type="button"
              class="btn btn-primary btn-sm"
              data-bs-toggle="modal"
              data-bs-target="#modalMarca"
            >
              Adicionar
            </button>
          </template>
        </card-component>
        <!-- Fim do card de listagem de marcas -->
      </div>
    </div>

    <!--Início do modal de inclusão de marca-->

    <modal-component id="modalMarca" titulo="Adicionar Marca">
      <template v-slot:alertas>
        <alert-component
          tipo="success"
          :detalhes="transacaoDetalhes"
          titulo="Cadastro realizado com sucesso!"
          v-if="transacaoStatus == 'adicionado'"
        ></alert-component>
        <alert-component
          tipo="danger"
          :detalhes="transacaoDetalhes"
          titulo="Erro ao tentar cadastrar a marca"
          v-if="transacaoStatus == 'erro'"
        ></alert-component>
      </template>
      <template v-slot:conteudo>
        <div class="form-group">
          <input-container-component
            titulo="Nome da marca"
            id="novoNome"
            id-help="novoNomeHelp"
            texto-opcional="Opcional. Informe o nome da marca"
          >
            <input
              type="text"
              class="form-control"
              id="novoNome"
              aria-describedby="novoNomeHelp"
              placeholder="Nome da marca"
              v-model="nomeMarca"
            />
          </input-container-component>
        </div>
        <div class="form-group">
          <input-container-component
            titulo="Imagem"
            id="novaImagem"
            id-help="novaImagemHelp"
            texto-opcional="Informe uma imagem no formato PNG"
          >
            <input
              type="file"
              class="form-control"
              id="novaImagem"
              aria-describedby="novaImagemHelp"
              placeholder="Selecione uma imagem"
              @change="carregarImagem($event)"
            />
          </input-container-component>
        </div>
      </template>
      <template v-slot:rodape>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Fechar
        </button>
        <button type="button" class="btn btn-primary" @click="salvar()">
          Salvar
        </button>
      </template>
    </modal-component>

    <!--Fim do modal de inclusão de marca-->

    <!--Início do modal de visualização de marca-->
    <modal-component id="modalMarcaVisualizar" titulo="Visualizar Marca">
      <template v-slot:alertas> </template>
      <template v-slot:conteudo>
        <input-container-component titulo="ID">
          <input
            type="text"
            class="form-control"
            :value="$store.state.item.id"
            disabled
          />
        </input-container-component>
        <input-container-component titulo="Nome da marca">
          <input
            type="text"
            class="form-control"
            :value="$store.state.item.nome"
            disabled
          />
        </input-container-component>
        <input-container-component titulo="Imagem">
          <img
            v-if="$store.state.item.imagem"
            :src="'storage/' + $store.state.item.imagem"
            alt="Imagem da marca"
            width="40"
            height="40"
          />
        </input-container-component>
        <input-container-component titulo="Data de criação">
          <input
            type="text"
            class="form-control"
            :value="$store.state.item.created_at"
            disabled
          />
        </input-container-component>
      </template>
      <template v-slot:rodape>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Fechar
        </button>
      </template>
    </modal-component>

    <!--Fim do modal de visualização de marca-->

    <!--Início do modal de remoção de marca-->
    <modal-component id="modalMarcaRemover" titulo="Remover Marca">
      <template v-slot:alertas> </template>
      <template v-slot:conteudo>
        <input-container-component titulo="ID">
          <input
            type="text"
            class="form-control"
            :value="$store.state.item.id"
            disabled
          />
        </input-container-component>
        <input-container-component titulo="Nome da marca">
          <input
            type="text"
            class="form-control"
            :value="$store.state.item.nome"
            disabled
          />
        </input-container-component>
      </template>
      <template v-slot:rodape>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Fechar
        </button>
        <button
          type="button"
          class="btn btn-danger"
          data-bs-dismiss="modal"
          @click="remover()"
        >
          Remover
        </button>
      </template>
    </modal-component>

    <!--Fim do modal de remoção de marca-->
  </div>
</template>

<script>
export default {
  computed: {
    token() {
      let token = document.cookie.split(";").find((indice) => {
        return indice.includes("token=");
      });

      if (token) {
        token = token.split("=")[1];
        token = "Bearer " + token;

        return token;
      }

      return false;
    },
  },

  data() {
    return {
      urlBase: "http://localhost:8000/api/marca",
      urlPaginacao: "",
      urlFiltro: "",
      nomeMarca: "",
      arquivoImagem: [],
      transacaoStatus: "",
      transacaoDetalhes: {},
      marcas: {
        data: [],
      },
      busca: {
        id: "",
        nome: "",
      },
    };
  },

  methods: {
    remover() {
      let confirmacao = confirm(
        "Tem certeza que deseja remover esse registro?"
      );

      if (!confirmacao) {
        return false;
      }

      let url = this.urlBase + "/" + this.$store.state.item.id;

      let formData = new FormData();
      formData.append("_method", "delete");

      let config = {
        headers: {
          Accept: "application/json",
          Authorization: this.token,
        },
      };

      axios
        .post(url, formData, config)
        .then((response) => {
          this.$store.state.transacao.status = "sucesso";
          this.$store.state.transacao.mensagem = response.data.msg;
          this.carregarLista();
        })
        .catch((errors) => {
          this.$store.state.transacao.status = "erro";
          this.$store.state.transacao.mensagem = errors.response.data.erro;
        });
    },
    pesquisar() {
      // console.log(this.busca)

      let filtro = "";

      for (let chave in this.busca) {
        if (this.busca[chave]) {
          if (filtro != "") {
            filtro += ";";
          }
          filtro += chave + ":like:" + this.busca[chave];
        }
      }

      if (filtro != "") {
        this.urlPaginacao = "page=1";
        this.urlFiltro = "&filtro=" + filtro;
      } else {
        this.urlFiltro = "";
      }

      this.carregarLista();
    },

    paginacao(item) {
      if (item.url) {
        this.urlPaginacao = item.url.split("?")[1];
        this.carregarLista();
      }
    },

    carregarLista() {
      let url = this.urlBase + "?" + this.urlPaginacao + this.urlFiltro;

      let config = {
        headers: {
          Accept: "application/json",
          Authorization: this.token,
        },
      };

      axios
        .get(url, config)
        .then((response) => {
          this.marcas = response.data;
        })
        .catch((errors) => {
          console.log(errors);
        });
    },

    carregarImagem(e) {
      this.arquivoImagem = e.target.files;
    },

    salvar() {
      let formData = new FormData();
      formData.append("nome", this.nomeMarca);
      formData.append("imagem", this.arquivoImagem[0]);

      let config = {
        headers: {
          "Content-Type": "multipart/form-data",
          Accept: "application/json",
          Authorization: this.token,
        },
      };

      axios
        .post(this.urlBase, formData, config)
        .then((response) => {
          this.transacaoStatus = "adicionado";
          this.transacaoDetalhes = {
            mensagem: "ID do registro: " + response.data.id,
          };
        })
        .catch((errors) => {
          this.transacaoStatus = "erro";
          this.transacaoDetalhes = {
            mensagem: errors.response.data.message,
            dados: errors.response.data.errors,
          };
        });
    },
  },
  mounted() {
    this.carregarLista();
  },
};
</script>