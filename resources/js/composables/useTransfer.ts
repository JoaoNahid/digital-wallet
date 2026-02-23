import type { Wallet, TransferFormData } from '@/types/wallet'
import { useForm } from '@inertiajs/vue3'
import { computed, ref, watch, type MaybeRefOrGetter, toRef } from 'vue'
import { useDebounceFn } from '@vueuse/core'

export function useTransfer(wallet: MaybeRefOrGetter<Wallet>) {
    const walletRef = toRef(wallet)

    const form = useForm<TransferFormData>({
        recipient_email: '',
        amount: '',
        description: '',
    })

    const recipientName = ref<string | null>(null)
    const isSearchingRecipient = ref(false)
    const recipientError = ref<string | null>(null)

    const amount = computed(() => parseFloat(form.amount) || 0)

    const isValid = computed(() => {
        return (
            form.recipient_email.includes('@') &&
            amount.value > 0 &&
            !recipientError.value
        )
    })

    const hasInsufficientBalance = computed(() => {
        return amount.value > walletRef.value.balance
    })

    const searchRecipient = useDebounceFn(async (email: string) => {
        if (!email || !email.includes('@')) {
            recipientName.value = null
            recipientError.value = null
            return
        }

        isSearchingRecipient.value = true
        recipientError.value = null

        try {
            const response = await fetch(`/api/users/search?email=${encodeURIComponent(email)}`, {
                credentials: 'include',
                headers: {
                    'Accept': 'application/json'
                }
            })
            const data = await response.json()

            if (data.user) {
                recipientName.value = data.user.name
                recipientError.value = null
            } else {
                recipientName.value = null
                recipientError.value = 'Usuário não encontrado'
            }
        } catch {
            recipientError.value = 'Erro ao buscar usuário'
        } finally {
            isSearchingRecipient.value = false
        }
    }, 500)

    watch(() => form.recipient_email, searchRecipient)

    function submit(onSuccess?: () => void) {
        console.log(form);
        
        form.post('/wallet/transfer', {
            preserveScroll: true,
            onSuccess: () => {
                form.reset()
                recipientName.value = null
                onSuccess?.()
            },
        })
    }

    function reset() {
        form.reset()
        form.clearErrors()
        recipientName.value = null
        recipientError.value = null
    }

    return {
        form,
        recipientName,
        isSearchingRecipient,
        recipientError,
        isValid,
        hasInsufficientBalance,
        submit,
        reset,
    }
}