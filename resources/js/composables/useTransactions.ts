import type {
    Transaction,
    TransactionType,
    TransactionStatus,
    TransactionTypeOption,
    TransactionStatusOption,
} from '@/types/wallet'
import { computed, ref, type MaybeRefOrGetter, toRef } from 'vue'

export function useTransactions(
    transactions: MaybeRefOrGetter<Transaction[]>
) {
    const transactionsRef = toRef(transactions)

    const hasTransactions = computed(() => transactionsRef.value.length > 0)

    function formatDate(dateString: string): string {
        return new Intl.DateTimeFormat('pt-BR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
        }).format(new Date(dateString))
    }

    function getAmountColorClass(formatted_amount: string): string {
        const firstChar = formatted_amount.trim().charAt(0)
        return firstChar === '+' ? 'text-green-600' : 'text-red-600'
    }

    return {
        transactions: transactionsRef,
        hasTransactions,
        formatDate,
        getAmountColorClass,
    }
}