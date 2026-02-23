<script setup lang="ts">
import type { TransactionShowProps } from '@/types/wallet'
import AppLayout from '@/layouts/AppLayout.vue'
import TransactionBadge from '@/components/transaction/TransactionBadge.vue'
import ReverseModal from '@/components/transaction/ReverseModal.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Separator } from '@/components/ui/separator'
import { Head, Link } from '@inertiajs/vue3'
import { useReverseTransaction } from '@/composables/useReverseTransaction'
import { useTransactions } from '@/composables/useTransactions'
import type { BreadcrumbItem } from '@/types'
import { ArrowLeftIcon, RotateCcwIcon } from 'lucide-vue-next'

const props = defineProps<TransactionShowProps>()
console.log(props)
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Carteira', href: '/wallet' },
    { title: 'Detalhes Transação' },
]

const { formatDate, getAmountColorClass } = useTransactions([])
const { isOpen, form, openModal, isValid, confirm } = useReverseTransaction()

function handleReverse() {
    openModal(props.transaction)
}
console.log(props.transaction.target_user);

</script>

<template>
    <Head title="Detalhes da Transação" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4" >
            <div class="flex items-center gap-4">
                <Button variant="ghost" size="icon" as-child>
                    <Link href="/wallet">
                        <ArrowLeftIcon class="h-4 w-4" />
                    </Link>
                </Button>
                <h1 class="text-2xl font-bold">Detalhes da Transação</h1>
            </div>

            <Card>
                <CardHeader>
                    <div class="flex items-center justify-between">
                        <CardTitle>{{ transaction.type_label }}</CardTitle>
                        <TransactionBadge
                            :status="transaction.status"
                            :label="transaction.status_label"
                            :variant="transaction.status_badge_variant"
                        />
                    </div>
                </CardHeader>

                <CardContent class="space-y-6">
                    <div class="text-center py-4">
                        <p class="text-4xl font-bold" :class="getAmountColorClass(transaction.type)">
                            {{ transaction.formatted_amount }}
                        </p>
                        <p class="text-sm text-muted-foreground mt-2">
                            {{ formatDate(transaction.created_at) }}
                        </p>
                    </div>

                    <Separator />

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <p class="text-sm text-muted-foreground">Saldo Antes</p>
                            <p class="font-medium">
                                R$ {{ transaction.balance_before.toFixed(2).replace('.', ',') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-muted-foreground">Saldo Depois</p>
                            <p class="font-medium">
                                R$ {{ transaction.balance_after.toFixed(2).replace('.', ',') }}
                            </p>
                        </div>
                    </div>

                    <div v-if="transaction.target_user">
                        <p class="text-sm text-muted-foreground">
                            {{ transaction.type === 'transfer_out' ? 'Enviado para' : 'Recebido de' }}
                        </p>
                        <p class="font-medium">{{ transaction.target_user.name }}</p>
                        <p class="text-sm text-muted-foreground">{{ transaction.target_user.email }}</p>
                    </div>

                    <div v-if="transaction.description">
                        <p class="text-sm text-muted-foreground">Descrição</p>
                        <p class="font-medium">{{ transaction.description }}</p>
                    </div>

                    <div v-if="transaction.reversed_at">
                        <p class="text-sm text-muted-foreground">Revertida em</p>
                        <p class="font-medium">{{ formatDate(transaction.reversed_at) }}</p>
                    </div>

                    <Separator />

                    <div class="flex justify-end">
                        <Button
                            v-if="transaction.can_be_reversed"
                            variant="destructive"
                            @click="handleReverse"
                        >
                            <RotateCcwIcon class="mr-2 h-4 w-4" />
                            Reverter Transação
                        </Button>
                    </div>
                </CardContent>
            </Card>
        </div>

        <ReverseModal
            v-model:open="isOpen"
            :transaction="transaction"
            :form="form"
            :is-valid="isValid"
            @confirm="confirm"
        />
    </AppLayout>
</template>