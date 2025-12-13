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

    function setProfile(data: ProfileData) {
        profileData.value = data;
    }

    function updateBalances(newBalances: {
        usd?: number;
        assets?: Record<string, number>;
    }) {
        if (!profileData.value) return;

        if (newBalances.usd !== undefined) {
            profileData.value.balance = newBalances.usd.toFixed(8);
        }

        if (newBalances.assets) {
            for (const [symbol, amount] of Object.entries(newBalances.assets)) {
                const existing = profileData.value.assets.find(
                    (a) => a.symbol === symbol,
                );

                const value = amount.toFixed(8);

                if (existing) {
                    existing.amount = value;
                } else {
                    profileData.value.assets.push({
                        symbol,
                        amount: value,
                    });
                }
            }
        }
    }

    function applyTrade(event: {
        base_symbol: string;
        quote_symbol: string;
        base_delta: string;
        quote_delta: string;
    }) {
        if (!profileData.value) return;

        const baseDelta = parseFloat(event.base_delta);
        const quoteDelta = parseFloat(event.quote_delta);

        updateBalances({
            usd: balances.value.usd + quoteDelta,
            assets: {
                [event.base_symbol]:
                    (balances.value.assets[event.base_symbol] || 0) + baseDelta,
            },
        });
    }

    return {
        profileData,
        balances,
        setProfile,
        updateBalances,
        applyTrade, // ✅ EXPOSE IT
    };
});
