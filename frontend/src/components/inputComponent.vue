<script setup lang="ts">
import { ref, watch } from 'vue'

// Props
const props = defineProps<{
  namen: string
  modelValue: string
}>()

// Emits
const emit = defineEmits<{
  (e: 'update:modelValue', value: string): void
}>()

// Lokalna vrednost inputa
const text = ref(props.modelValue)

// Ko se lokalno polje spremeni, pošlji spremembo staršu
watch(text, (val) => {
  emit('update:modelValue', val)
})
</script>

<template>
  <div class="input-field">
    <label :for="props.namen">{{ props.namen }}</label>
    <input v-model="text" :id="props.namen" :placeholder="props.namen" method="POST" >
  </div>
</template>

<style scoped>
.input-field {
  display: flex;
  flex-direction: column;
  margin-bottom: 10px;
}

.input-field input {
  border: 2px solid #ccc;
  border-radius: 5px;
  padding: 6px;
}
</style>