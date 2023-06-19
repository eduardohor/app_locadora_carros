<template>
  <table class="table table-hover">
    <thead>
      <tr>
        <th scope="col" v-for="item, key in titulos" :key="key" class="text-uppercase">{{ item.titulo }}</th>
      </tr>
    </thead>
    <tbody>

      <tr v-for="dados, chave in dadosFiltrados" :key="chave">
        <td v-for="item, chaveItem in dados" :key="chaveItem">
          <span v-if="titulos[chaveItem].tipo == 'texto'">{{ item }}</span>
          <span v-if="titulos[chaveItem].tipo == 'data'">{{ '...' + item }}</span>
          <span v-if="titulos[chaveItem].tipo == 'imagem'">
            <img :src="'/storage/' + item" alt="Imagem" width="40">
          </span>
        </td>
      </tr>

    </tbody>
  </table>
</template>

<script>
export default {
  props: ['dados', 'titulos'],

  computed: {
    dadosFiltrados() {

      let campos = Object.keys(this.titulos)
      let dadosFiltados = []

      this.dados.map((item, chave) => {

        let itemFiltrado = {}

        campos.forEach(campo => {

          itemFiltrado[campo] = item[campo]
        })

        dadosFiltados.push(itemFiltrado)

      })
      return dadosFiltados
    }
  }
}
</script>