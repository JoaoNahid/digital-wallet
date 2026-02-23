<script setup lang="ts">
import type { Wallet } from '@/types/wallet'
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Textarea } from '@/components/ui/textarea'
import { Alert, AlertDescription } from '@/components/ui/alert'
import MoneyInput from '@/components/shared/MoneyInput.vue'
import { useTransfer } from '@/composables/useTransfer'
import { AlertCircleIcon, CheckCircleIcon, LoaderIcon } from 'lucide-vue-next'

interface Props {
    open: boolean
    wallet: Wallet
}

const props = defineProps<Props>()

const emit = defineEmits<{
    'update:open': [value: boolean]
}>()

const {
    form,
    recipientName,
    isSearchingRecipient,
    recipientError,
    isValid,
    hasInsufficientBalance,
    submit,
    reset,
} = useTransfer(() => props.wallet)

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
                <DialogTitle>Transferir</DialogTitle>
                <DialogDescription>
                    Envie dinheiro para outro usuário.
                </DialogDescription>
            </DialogHeader>

            <form @submit.prevent="handleSubmit" class="space-y-4">
                <div class="space-y-2">
                    <Label for="recipient-email">E-mail do destinatário</Label>
                    <div class="relative">
                        <Input
                            id="recipient-email"
                            type="email"
                            v-model="form.recipient_email"
                            placeholder="usuario@email.com"
                            :class="{ 'border-destructive': form.errors.recipient_email || recipientError }"
                        />
                        <div v-if="isSearchingRecipient" class="absolute right-3 top-1/2 -translate-y-1/2">
                            <LoaderIcon class="h-4 w-4 animate-spin text-muted-foreground" />
                        </div>
                    </div>
                    <p v-if="form.errors.recipient_email" class="text-sm text-destructive">
                        {{ form.errors.recipient_email }}
                    </p>
                    <p v-else-if="recipientError" class="text-sm text-destructive">
                        {{ recipientError }}
                    </p>
                    <p v-else-if="recipientName" class="text-sm text-green-600 flex items-center gap-1">
                        <CheckCircleIcon class="h-4 w-4" />
                        {{ recipientName }}
                    </p>
                </div>

                <MoneyInput
                    v-model="form.amount"
                    id="transfer-amount"
                    label="Valor"
                    placeholder="R$ 0,00"
                    :error="form.errors.amount"
                />

                <Alert v-if="hasInsufficientBalance" variant="destructive">
                    <AlertCircleIcon class="h-4 w-4" />
                    <AlertDescription>
                        Saldo insuficiente. Disponível: {{ wallet.formatted_balance }}
                    </AlertDescription>
                </Alert>

                <div class="space-y-2">
                    <Label for="transfer-description">Descrição (opcional)</Label>
                    <Textarea
                        id="transfer-description"
                        v-model="form.description"
                        placeholder="Ex: Pagamento de conta"
                        rows="2"
                    />
                </div>

                <DialogFooter>
                    <Button type="button" variant="outline" @click="handleClose">
                        Cancelar
                    </Button>
                    <Button
                        type="submit"
                        :disabled="!isValid || hasInsufficientBalance || form.processing"
                    >
                        {{ form.processing ? 'Transferindo...' : 'Transferir' }}
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>