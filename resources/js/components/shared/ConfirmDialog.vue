<script setup lang="ts">
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog'
import { Button } from '@/components/ui/button'

interface Props {
    open: boolean
    title: string
    description?: string
    confirmText?: string
    cancelText?: string
    variant?: 'default' | 'destructive'
    loading?: boolean
    isValid?: boolean
}

const props = withDefaults(defineProps<Props>(), {
    confirmText: 'Confirmar',
    cancelText: 'Cancelar',
    variant: 'default',
    loading: false,
    isValid: false
})

const emit = defineEmits<{
    'update:open': [value: boolean]
    confirm: []
    cancel: []
}>()

function handleClose() {
    emit('update:open', false)
    emit('cancel')
}

function handleConfirm() {
    emit('confirm')
}
</script>

<template>
    <Dialog :open="open" @update:open="emit('update:open', $event)">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>{{ title }}</DialogTitle>
                <DialogDescription v-if="description">
                    {{ description }}
                </DialogDescription>
            </DialogHeader>

            <slot />

            <DialogFooter>
                <Button variant="outline" :disabled="loading" @click="handleClose">
                    {{ cancelText }}
                </Button>
                <Button
                    :variant="variant"
                    :disabled="loading || isValid"
                    @click="handleConfirm"
                >
                    <span v-if="loading">Processando...</span>
                    <span v-else>{{ confirmText }}</span>
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>