package dev.akat.smartmeter.model

import com.google.gson.annotations.SerializedName

data class AccountInfo(
    @field:SerializedName("account")
    val account: Account,
    @field:SerializedName("abonent")
    val abonent: Abonent,
    @field:SerializedName("object")
    val objectInfo: ObjectInfo,
    @field:SerializedName("company")
    val company: Company,
    @field:SerializedName("access")
    val access: Boolean,
    @field:SerializedName("debt")
    val debt: Double,
    @field:SerializedName("summa")
    val summa: Double,
    @field:SerializedName("services")
    val services: List<ServiceInfo>
)