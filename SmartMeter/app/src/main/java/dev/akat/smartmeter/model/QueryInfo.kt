package dev.akat.smartmeter.model

import com.google.gson.annotations.SerializedName

data class QueryInfo(
    @field:SerializedName("query")
    val query: Query,
    @field:SerializedName("company")
    val company: Company
)