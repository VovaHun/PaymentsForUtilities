package dev.akat.smartmeter.model

import com.google.gson.annotations.SerializedName
import java.util.*

data class Query(
    @field:SerializedName("id")
    val id: Long,
    @field:SerializedName("date")
    val date: Date,
    @field:SerializedName("status")
    val status: Int,
    @field:SerializedName("status_name")
    val statusName: String,
    @field:SerializedName("text")
    val text: String,
    @field:SerializedName("answer")
    val answer: String
)