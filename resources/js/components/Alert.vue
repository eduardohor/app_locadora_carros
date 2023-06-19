<template>
  <div :class="estilo" role="alert">
    {{ titulo }}
    <hr>
    <p>{{ detalhes.mensagem }}</p>
    <ul v-if="detalhes.dados">
      <li v-for="erro, key in detalhes.dados" :key="key">
        {{ erro[0] }}
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  props: ['tipo', 'detalhes', 'titulo'],
  computed: {
    estilo() {
      return 'alert alert-' + this.tipo
    }
  }
}
</script>
