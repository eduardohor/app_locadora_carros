<template>
  <table class="table table-hover">
    <thead>
      <tr>
        <th
          scope="col"
          v-for="(item, key) in titulos"
          :key="key"
          class="text-uppercase"
        >
          {{ item.titulo }}
        </th>
        <th v-if="visualizar.visivel || atualizar || remover.visivel"></th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(dados, chave) in dadosFiltrados" :key="chave">
        <td v-for="(item, chaveItem) in dados" :key="chaveItem">
          <span v-if="titulos[chaveItem].tipo == 'texto'">{{ item }}</span>
          <span v-if="titulos[chaveItem].tipo == 'data'">{{
            "..." + item
          }}</span>
          <span v-if="titulos[chaveItem].tipo == 'imagem'">
            <img :src="'/storage/' + item" alt="Imagem" width="40" />
          </span>
        </td>
        <td v-if="visualizar.visivel || atualizar || remover.visivel">
          <button
            v-if="visualizar.visivel"
            class="btn btn-outline-primary btn-sm"
            :data-bs-toggle="visualizar.dataBsToggle"
            :data-bs-target="visualizar.dataBsTarget"
            @click="setStore(dados)"
          >
            Visualizar
          </button>
          <button v-if="atualizar" class="btn btn-outline-primary btn-sm">
            Atualizar
          </button>
          <button
            v-if="remover.visivel"
            class="btn btn-outline-danger btn-sm"
            :data-bs-toggle="remover.dataBsToggle"
            :data-bs-target="remover.dataBsTarget"
            @click="setStore(dados)"
          >
            Remover
          </button>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script>
export default {
  props: ["dados", "titulos", "visualizar", "atualizar", "remover"],
  methods: {
    setStore(dados) {
      this.$store.state.item = dados;
    },
  },
  computed: {
    dadosFiltrados() {
      let campos = Object.keys(this.titulos);
      let dadosFiltados = [];

      this.dados.map((item, chave) => {
        let itemFiltrado = {};

        campos.forEach((campo) => {
          itemFiltrado[campo] = item[campo];
        });

        dadosFiltados.push(itemFiltrado);
      });
      return dadosFiltados;
    },
  },
};
</script>
