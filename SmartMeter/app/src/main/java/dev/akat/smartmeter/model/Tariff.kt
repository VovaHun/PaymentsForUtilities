package dev.akat.smartmeter.model

import com.google.gson.annotations.SerializedName


data class Tariff(
    @field:SerializedName("id")
    val id: Long,
    @field:SerializedName("name")
    val name: String,
    @field:SerializedName("is_normative")
    val isNormative: Int,
    @field:SerializedName("price")
    val price: Double
)