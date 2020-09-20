package dev.akat.smartmeter.model

import com.google.gson.annotations.SerializedName

data class Company(
    @field:SerializedName("id")
    val id: Long,
    @field:SerializedName("name")
    val name: String
)