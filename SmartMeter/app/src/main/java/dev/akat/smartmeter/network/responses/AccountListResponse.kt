package dev.akat.smartmeter.network.responses

import com.google.gson.annotations.SerializedName
import dev.akat.smartmeter.model.AccountSummary

data class AccountListResponse(
    @field:SerializedName("ok")
    val ok: Boolean,
    @field:SerializedName("error_code")
    val errorCode: Int,
    @field:SerializedName("error")
    val error: String,
    @field:SerializedName("data")
    val data: List<AccountSummary>? = null
)