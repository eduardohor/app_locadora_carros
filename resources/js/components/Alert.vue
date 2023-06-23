<template>
  <div :class="estilo + ' alert-dismissible fade show'" role="alert">
    {{ titulo }}
    <hr />
    <p>{{ detalhes.mensagem }}</p>
    <ul v-if="detalhes.dados">
      <li v-for="(erro, key) in detalhes.dados" :key="key">
        {{ erro[0] }}
      </li>
    </ul>
    <button
      type="button"
      class="btn-close"
      data-bs-dismiss="alert"
      aria-label="Close"
    ></button>
  </div>
</template>

<script>
export default {
  props: ["tipo", "detalhes", "titulo"],
  computed: {
    estilo() {
      return "alert alert-" + this.tipo;
    },
  },
};
</script>
