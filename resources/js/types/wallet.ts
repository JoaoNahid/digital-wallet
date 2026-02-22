// Enums
export type TransactionType = 'deposit' | 'transfer_out' | 'transfer_in' | 'reversal'
export type TransactionStatus = 'pending' | 'completed' | 'reversed' | 'failed'

export interface Wallet {
    id: string
    user_id: number
    balance: number
    formatted_balance: string
    is_negative: boolean
    created_at: string
    updated_at: string
}

// Form Data
export interface DepositFormData {
    amount: string
    description: string
}