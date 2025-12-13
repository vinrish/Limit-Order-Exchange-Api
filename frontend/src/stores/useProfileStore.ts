export interface Asset {
    symbol: string;
    amount: string;
    locked_amount?: string;
}

export interface ProfileData {
    id: string;
    name: string;
    email: string;
    balance: string;
    assets: Asset[];
}

export const useProfileStore = defineStore("profile", () => {
    const profileData = ref<ProfileData | null>(null);

    const balances = computed(() => {
        if (!profileData.value) return { usd: 0, assets: {} };

        const usd = parseFloat(profileData.value.balance) || 0;

        const assetsMap: Record<string, number> = {};
        profileData.value.assets.forEach((a) => {
            assetsMap[a.symbol] = parseFloat(a.amount) || 0;
        });

        return { usd, assets: assetsMap };
    });

    const setProfile = (data: ProfileData) => {
        profileData.value = data;
    };

    const updateBalances = (newBalances: {
        usd?: number;
        assets?: Record<string, number>;
    }) => {
        if (!profileData.value) return;

        if (newBalances.usd !== undefined) {
            profileData.value.balance = newBalances.usd.toFixed(8);
        }

        if (newBalances.assets !== undefined) {
            const assetsArray = profileData.value.assets || [];
            for (const symbol in newBalances.assets) {
                const index = assetsArray.findIndex((a) => a.symbol === symbol);
                // @ts-ignore
                const amountStr = newBalances.assets[symbol].toFixed(8);
                if (index >= 0) {
                    // @ts-ignore
                    assetsArray[index].amount = amountStr;
                } else {
                    assetsArray.push({ symbol, amount: amountStr });
                }
            }
            profileData.value.assets = assetsArray;
        }
    };

    return {
        profileData,
        balances,
        setProfile,
        updateBalances,
    };
});
