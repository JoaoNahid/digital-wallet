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
import { Label } from '@/components/ui/label'
import { Textarea } from '@/components/ui/textarea'
import MoneyInput from '@/components/shared/MoneyInput.vue'
import { useDeposit } from '@/composables/useDeposit'

interface Props {
    open: boolean
}

defineProps<Props>()

const emit = defineEmits<{
    'update:open': [value: boolean]
}>()

const { form, isValid, submit, reset } = useDeposit()

function handleClose() {
    reset()
    emit('update:open', false)
}

function handleSubmit() {
    submit(() => {
        emit('update:open', false)
    })
}
</script>

<template>
    <Dialog :open="open" @update:open="emit('update:open', $event)">
        <DialogContent class="sm:max-w-md">
            <DialogHeader>
                <DialogTitle>Depositar</DialogTitle>
                <DialogDescription>
                    Adicione saldo à sua carteira.
                </DialogDescription>
            </DialogHeader>

            <form @submit.prevent="handleSubmit" class="space-y-4">
                <MoneyInput
                    v-model="form.amount"
                    id="deposit-amount"
                    label="Valor"
                    placeholder="R$ 0,00"
                    :error="form.errors.amount"
                />

                <div class="space-y-2">
                    <Label for="deposit-description">Descrição (opcional)</Label>
                    <Textarea
                        id="deposit-description"
                        v-model="form.description"
                        placeholder="Ex: Depósito inicial"
                        rows="2"
                    />
                </div>

                <DialogFooter>
                    <Button type="button" variant="outline" @click="handleClose">
                        Cancelar
                    </Button>
                    <Button
                        type="submit"
                        :disabled="!isValid || form.processing"
                    >
                        {{ form.processing ? 'Depositando...' : 'Depositar' }}
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>