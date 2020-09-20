package dev.akat.smartmeter.model

import com.google.gson.annotations.SerializedName

data class Calculation(
    @field:SerializedName("type")
    val type: Int,
    @field:SerializedName("name")
    val name: String,
    @field:SerializedName("coefficient")
    val coefficient: Double
)