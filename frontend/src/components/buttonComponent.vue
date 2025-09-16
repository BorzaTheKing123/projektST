<script setup lang="ts">
import { defineProps, withDefaults, defineEmits } from 'vue'

// forward the native click event
const emit = defineEmits<{
  (e: 'click', event: MouseEvent): void
}>()

const handleClick = (event: MouseEvent) => {
  emit('click', event) // emit the native click event
}

const props = withDefaults(defineProps<{
  text: string
  disabled?: boolean
}>(), {
  disabled: false
})
</script>

<template>
  <button type="button" @click="handleClick" :disabled="disabled">{{ props.text }}</button>
</template>

<style scoped>
button {
  padding: 8px 12px;
  background-color: #4CAF50;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
}
button:not(:disabled):hover {
  background-color: #45a049;
}

button:disabled {
  background-color: grey;
}
</style>