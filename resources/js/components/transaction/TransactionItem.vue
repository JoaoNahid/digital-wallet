<script setup lang="ts">
import type { Transaction } from '@/types/wallet'
import { useTransactions } from '@/composables/useTransactions'
import TransactionBadge from './TransactionBadge.vue'
import { Link } from '@inertiajs/vue3'
import {
    ArrowDownIcon,
    ArrowUpIcon,
    RefreshCwIcon,
    RotateCcwIcon,
} from 'lucide-vue-next'
import { computed } from 'vue'

interface Props {
    transaction: Transaction
}

const props = defineProps<Props>()

const { formatDate, getAmountColorClass } = useTransactions([])

const icon = computed(() => {
    const iconMap = {
        deposit: ArrowDownIcon,
        transfer_out: ArrowUpIcon,
        transfer_in: ArrowDownIcon,
        reversal: RotateCcwIcon,
    }
    return iconMap[props.transaction.type]
})

const iconColorClass = computed(() => {
    const colorMap = {
        deposit: 'text-green-600',
        transfer_out: 'text-red-600',
        transfer_in: 'text-green-600',
        reversal: 'text-orange-600',
    }
    return colorMap[props.transaction.type]
})
</script>

<template>
    <Link
        :href="`/transactions/${transaction.id}`"
        class="flex items-center gap-4 p-4 hover:bg-muted/50 rounded-lg transition-colors"
    >
        <div
            class="flex h-10 w-10 items-center justify-center rounded-full bg-muted"
            :class="iconColorClass"
        >
            <component :is="icon" class="h-5 w-5" />
        </div>

        <div class="flex-1 min-w-0">
            <p class="font-medium truncate">{{ transaction.type_label }}</p>
            <p class="text-sm text-muted-foreground truncate">
                <span v-if="transaction.target_user">
                    {{ transaction.type === 'transfer_out' ? 'Para: ' : 'De: ' }}
                    {{ transaction.target_user.name }}
                </span>
                <span v-else-if="transaction.description">
                    {{ transaction.description }}
                </span>
                <span v-else>
                    {{ formatDate(transaction.created_at) }}
                </span>
            </p>
        </div>

        <div class="text-right">
            <p class="font-medium" :class="getAmountColorClass(transaction.formatted_amount)">
                {{ transaction.formatted_amount }}
            </p>
            <TransactionBadge
                :status="transaction.status"
                :label="transaction.status_label"
                :variant="transaction.status_badge_variant"
            />
        </div>
    </Link>
</template>