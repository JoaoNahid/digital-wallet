<script setup lang="ts">
import type { Transaction, ReverseFormData } from '@/types/wallet'
import ConfirmDialog from '@/components/shared/ConfirmDialog.vue'
import { Label } from '@/components/ui/label'
import { Textarea } from '@/components/ui/textarea'
import { Alert, AlertDescription } from '@/components/ui/alert'
import { AlertTriangleIcon } from 'lucide-vue-next'
import type { InertiaForm } from '@inertiajs/vue3'

interface Props {
    open: boolean
    transaction: Transaction | null
    form: InertiaForm<ReverseFormData>
    isValid: boolean
}

const props = defineProps<Props>()

const emit = defineEmits<{
    'update:open': [value: boolean]
    confirm: []
}>()

</script>

<template>
    <ConfirmDialog
        :open="open"
        title="Reverter Transação"
        description="Esta ação não pode ser desfeita. O saldo será ajustado de acordo."
        confirm-text="Reverter"
        variant="destructive"
        :loading="form.processing"
        :is-valid="!isValid"
        @update:open="emit('update:open', $event)"
        @confirm="emit('confirm')"
    >
        <div v-if="transaction" class="space-y-4 py-4">
            <Alert>
                <AlertTriangleIcon class="h-4 w-4" />
                <AlertDescription>
                    Você está prestes a reverter uma transação de
                    <strong>{{ transaction.formatted_amount }}</strong>.
                </AlertDescription>
            </Alert>

            <div class="space-y-2">
                <Label for="reverse-reason">Motivo*</Label>
                <Textarea
                    id="reverse-reason"
                    v-model="form.reason"
                    placeholder="Informe o motivo da reversão"
                    rows="2"
                />
            </div>
        </div>
    </ConfirmDialog>
</template>