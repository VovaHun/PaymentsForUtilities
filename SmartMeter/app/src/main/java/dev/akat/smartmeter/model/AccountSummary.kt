package dev.akat.smartmeter.model

import com.google.gson.annotations.SerializedName

data class AccountSummary(
    @field:SerializedName("account_id")
    val id: Long,
    @field:SerializedName("account_name")
    val accountName: String,
    @field:SerializedName("abonent_name")
    val abonentName: String,
    @field:SerializedName("object_name")
    val objectName: String,
    @field:SerializedName("object_address")
    val objectAddress: String,
    @field:SerializedName("company_name")
    val companyName: String
)