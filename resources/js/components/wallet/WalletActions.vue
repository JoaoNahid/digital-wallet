<script setup lang="ts">
import { Button } from '@/components/ui/button'
import { ArrowDownIcon, ArrowUpIcon } from 'lucide-vue-next'

const emit = defineEmits<{
    deposit: []
    transfer: []
}>()
</script>

<template>
    <div class="flex gap-4">
        <Button class="flex-1 cursor-pointer" @click="emit('deposit')">
            <ArrowDownIcon class="mr-2 h-4 w-4" />
            Depositar
        </Button>
        <Button class="flex-1 cursor-pointer" variant="outline" @click="emit('transfer')">
            <ArrowUpIcon class="mr-2 h-4 w-4" />
            Transferir
        </Button>
    </div>
</template>
