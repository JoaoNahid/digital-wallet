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

export interface Transaction {
    id: string
    wallet_id: string
    target_wallet_id: string | null
    type: TransactionType
    type_label: string
    amount: number
    formatted_amount: string
    balance_before: number
    balance_after: number
    status: TransactionStatus
    status_label: string
    status_badge_variant: 'warning' | 'success' | 'secondary' | 'destructive'
    description: string | null
    can_be_reversed: boolean
    reversed_at: string | null
    created_at: string
    updated_at: string
    target_user?: {
        id: number
        name: string
        email: string
    }
}

// Form Data
export interface DepositFormData {
    amount: string
    description: string
}

export interface TransferFormData {
    recipient_email: string
    amount: string
    description: string
}

// Page
export interface WalletIndexProps { // /wallet
    wallet: Wallet
}


export interface TransactionShowProps {
    transaction: Transaction
}